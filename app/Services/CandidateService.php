<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Hire;

class CandidateService
{
    public function contact(int $candidateId, int $companyId): void
    {
        Contact::create([
            'candidate_id' => $candidateId,
            'company_id' => $companyId,
        ]);
    }

    public function hire(int $candidateId, int $companyId): void
    {
        Hire::create([
            'candidate_id' => $candidateId,
            'company_id' => $companyId,
        ]);
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