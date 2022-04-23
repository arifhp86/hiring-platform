<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    public const CONTACT_COINS = 5;

    public function contacts()
    {
        return $this->belongsToMany(Company::class, 'contacts');
    }

    public function hires()
    {
        return $this->belongsToMany(Company::class, 'hires');
    }

    public function strengths()
    {
        return $this->belongsToMany(CandidateStrength::class);
    }
}
