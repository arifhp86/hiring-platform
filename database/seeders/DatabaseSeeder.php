<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateStrength;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $candidateStrengths = CandidateStrength::factory()
            ->count(4)
            ->state(new Sequence(
                ['name' => 'PHP'],
                ['name' => 'Laravel'],
                ['name' => 'Vue.js'],
                ['name' => 'TailwindCSS'],
            ))
            ->create();
        
        Candidate::factory(3)->hasAttached($candidateStrengths, [], 'strengths')->create();
        Company::factory(1)->create();
        Wallet::factory(1)->create();
    }
}
