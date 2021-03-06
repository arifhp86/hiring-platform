<?php

namespace Tests\Feature;

use App\Mail\CandidateContacted;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactACandidateTest extends TestCase
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

    public function test_can_view_candiates_index_page()
    {
        $response = $this->get('/candidate-list');
        $response->assertStatus(200);
    }
    
    public function test_candidate_can_be_contacted()
    {
        $response = $this->post("candidates/{$this->candidate->id}/contact");

        $response->assertStatus(200)
            ->assertJson([
                'wallet_balance' => Wallet::INITIAL_COINS - Candidate::CONTACT_COINS,
            ]);

        $this->assertDatabaseHas('contacts', [
            'candidate_id' => $this->candidate->id,
            'company_id' => $this->company->id,
        ]);
    }

    public function test_contacting_candidate_charges_coins()
    {
        $this->post("candidates/{$this->candidate->id}/contact");

        $this->assertEquals(
            Wallet::INITIAL_COINS - Candidate::CONTACT_COINS,
            $this->company->wallet->coins
        );
    }

    public function test_can_not_contact_without_enough_coins()
    {
        $wallet = $this->company->wallet;
        $wallet->coins = 0;
        $wallet->save();

        $response = $this->post("candidates/{$this->candidate->id}/contact");
        
        $response->assertStatus(422);
    }

    public function test_can_not_contact_if_already_contacted()
    {
        $this->post("candidates/{$this->candidate->id}/contact");

        $response = $this->post("candidates/{$this->candidate->id}/contact");
        $response->assertStatus(400);
    }

    public function test_email_sent_with_contact()
    {
        Mail::fake();

        $this->post("candidates/{$this->candidate->id}/contact");

        Mail::assertSent(CandidateContacted::class);
    }
}
