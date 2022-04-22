<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactACandidateTest extends TestCase
{
    use RefreshDatabase;

    private $company;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->company = Company::find(1);
        $this->candidate = Candidate::find(1);
    }

    public function test_can_view_candiates_index_page()
    {
        $response = $this->get('/candidates');
        $response->assertStatus(200)
            ->assertSee('Your wallet has: 20 coins');
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

    public function test_con_not_contact_if_already_contacted()
    {
        $this->post("candidates/{$this->candidate->id}/contact");

        $response = $this->post("candidates/{$this->candidate->id}/contact");
        $response->assertStatus(400);
    }
}
