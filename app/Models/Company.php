<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Candidate::class, 'contacts');
    }

    public function hires()
    {
        return $this->belongsToMany(Candidate::class, 'hires');
    }
}
