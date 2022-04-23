<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Hire;
use Illuminate\Support\Facades\Mail;

class CandidateService
{
    public function contact(Candidate $candidate, Company $company): void
    {
        Contact::create([
            'candidate_id' => $candidate->id,
            'company_id' => $company->id,
        ]);

        $messageText = "Hey there, {$company->name} wants to contact you, we provided your contact information to them, hopefully you will here form them soon.";
        $this->sendEmail($candidate->email, 'You have been contacted!', $messageText);
    }

    public function hire(Candidate $candidate, Company $company): void
    {
        Hire::create([
            'candidate_id' => $candidate->id,
            'company_id' => $company->id,
        ]);

        $messageText = "Congratulatons, you have been hired by {$company->name}";
        $this->sendEmail($candidate->email, 'You have been hired!', $messageText);
    }

    public function alreadyContacted(Candidate $candidate, Company $company): bool
    {
        return $candidate->contacts->contains(fn($value) => $value->id === $company->id);
    }

    public function alreadyHired(Candidate $candidate, Company $company)
    {
        return $candidate->hires->contains(fn($value) => $value->id === $company->id);
    }

    private function sendEmail(string $to, string $subject, string $message)
    {
        Mail::raw($message, function($message) use($to, $subject) {
            $message->to($to);
            $message->subject($subject);
            $message->from('example@email.com');
        });
    }
}