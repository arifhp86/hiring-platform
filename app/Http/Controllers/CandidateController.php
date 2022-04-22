<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateContact;
use App\Models\Company;
use App\Services\Exceptions\NotEnoughCoinException;
use App\Services\WalletService;

class CandidateController extends Controller
{
    public function __construct(private WalletService $walletService)
    { 
    }

    public function index()
    {
        $candidates = Candidate::all();
        $coins = Company::find(1)->wallet->coins;

        return view('candidates.index', compact('candidates', 'coins'));
    }

    public function contact($id)
    {
        $candidate = Candidate::findOrfail($id);
        $company = Company::find(1);

        try {
            $this->walletService->charge($company->wallet, 5);

            CandidateContact::create([
                'candidate_id' => $candidate->id,
                'company_id' => $company->id,
            ]);
            
        } catch(NotEnoughCoinException $e) {
            return response()->json([
                'message' => 'Sorry, you do not have enough coins to perform this action',
            ], 422);
        }

        return response()->json([
            'wallet_balance' => $company->wallet->fresh()->coins,
        ]);
    }

    public function hire()
    {
        // @todo
        // Your code goes here...
    }
}
