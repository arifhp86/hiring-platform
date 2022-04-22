<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HireACandidateTest extends TestCase
{
    use RefreshDatabase;

    private $company;
    
    private $candidate;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->company = Company::find(1);
        $this->candidate = Candidate::find(1);
    }

    public function test_can_hire_candidate()
    {
        $this->post("candidates/{$this->candidate->id}/contact");
        $response = $this->post("candidates/{$this->candidate->id}/hire");

        $response->assertStatus(200)
            ->assertJson([
                'wallet_balance' => Wallet::INITIAL_COINS,
            ]);

        $this->assertDatabaseHas('hires', [
            'candidate_id' => $this->candidate->id,
            'company_id' => $this->company->id,
        ]);
    }

    public function test_can_not_hire_without_contacting_first()
    {
        $response = $this->post("candidates/{$this->candidate->id}/hire");

        $response->assertStatus(400);
    }

    public function test_can_not_hire_already_hired_candidate()
    {
        $this->post("candidates/{$this->candidate->id}/contact");
        $this->post("candidates/{$this->candidate->id}/hire");
        $response = $this->post("candidates/{$this->candidate->id}/hire");

        $response->assertStatus(400);
    }
}
