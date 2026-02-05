<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\LoginHistory;

class SessionExpiryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->user = User::create(['name'=>'Cust','email'=>'cust@example.com','password'=>bcrypt('password'),'role'=>'customer']);
    }

    public function test_user_is_logged_out_if_login_over_one_week_ago_without_recent_login()
    {
        $this->actingAs($this->user)
            ->withSession(['login_at' => now()->subWeeks(2)->timestamp])
            ->get('/customer')
            ->assertRedirect(route('customer.login'));
    }

    public function test_user_is_allowed_if_recent_login_history_exists()
    {
        // create recent login history within 7 days
        LoginHistory::create(['user_id'=>$this->user->id,'role'=>'customer','ip'=>'127.0.0.1']);

        $this->actingAs($this->user)
            ->withSession(['login_at' => now()->subWeeks(2)->timestamp])
            ->get('/customer')
            ->assertStatus(200);
    }
}
