<?php

namespace App\Services;

use App\Mail\CandidateContacted;
use App\Mail\CandidateHired;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;

class CandidateService
{
    public function contact(Candidate $candidate, Company $company): void
    {
        $company->contacts()->attach($candidate->id, ['contacted_at' => now()]);

        Mail::to($candidate->email)->send(new CandidateContacted($company));
    }

    public function hire(Candidate $candidate, Company $company): void
    {
        $company->hires()->attach($candidate->id, ['hired_at' => now()]);

        Mail::to($candidate->email)->send(new CandidateHired($company));
    }

    public function alreadyContacted(Candidate $candidate, Company $company): bool
    {
        return $candidate->contacts->contains(fn($value) => $value->id === $company->id);
    }

    public function alreadyHired(Candidate $candidate, Company $company)
    {
        return $candidate->hires->contains(fn($value) => $value->id === $company->id);
    }
}
