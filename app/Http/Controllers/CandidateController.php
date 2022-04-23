<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use App\Services\CandidateService;
use App\Services\Exceptions\NotEnoughCoinException;
use App\Services\WalletService;

class CandidateController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private CandidateService $candidateService
    ) {
    }

    public function index()
    {
        $candidates = Candidate::with('strengths')->get();
        $company = Company::with('contacts', 'hires')->find(1);
        $coins = $company->wallet->coins;

        return response()->json(compact('candidates', 'coins', 'company'));
    }

    public function contact($id)
    {
        $candidate = Candidate::findOrfail($id);
        $company = Company::with('contacts')->find(1);

        if (
            $this->candidateService->alreadyContacted($candidate, $company) ||
            $this->candidateService->alreadyHired($candidate, $company)
        ) {
            return response()->json([
                'message' => 'Invalid request'
            ], 400);
        }

        try {
            $this->walletService->charge($company->wallet, Candidate::CONTACT_COINS);

            $this->candidateService->contact($candidate, $company);
        } catch (NotEnoughCoinException $e) {
            return response()->json([
                'message' => 'Sorry, you do not have enough coins to perform this action',
            ], 422);
        }

        return response()->json([
            'wallet_balance' => $company->wallet->fresh()->coins,
        ]);
    }

    public function hire($id)
    {
        $candidate = Candidate::findOrfail($id);
        $company = Company::with('contacts')->find(1);

        if (
            ! $this->candidateService->alreadyContacted($candidate, $company) ||
            $this->candidateService->alreadyHired($candidate, $company)
        ) {
            return response()->json([
                'message' => 'Invalid request'
            ], 400);
        }

        $this->walletService->refund($company->wallet, Candidate::CONTACT_COINS);
        $this->candidateService->hire($candidate, $company);

        return response()->json([
            'wallet_balance' => $company->wallet->fresh()->coins,
        ]);
    }
}
