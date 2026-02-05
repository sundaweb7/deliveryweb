<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\LoginHistory;

class AdminLoginHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_login_histories_page()
    {
        $admin = User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'role'=>'admin']);

        $this->actingAs($admin)
            ->get(route('admin.login_histories.index'))
            ->assertStatus(200)
            ->assertSee('Login Histories');
    }

    public function test_admin_can_fetch_login_histories_data()
    {
        $admin = User::create(['name'=>'Admin2','email'=>'admin2@example.com','password'=>bcrypt('password'),'role'=>'admin']);
        $user = User::create(['name'=>'User','email'=>'user@example.com','password'=>bcrypt('password'),'role'=>'customer']);

        LoginHistory::create(['user_id'=>$user->id,'role'=>'customer','ip'=>'127.0.0.1','user_agent'=>'PHPUnit']);

        $response = $this->actingAs($admin)->get(route('admin.login_histories.data'));
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertArrayHasKey('data',$json);
        $this->assertGreaterThanOrEqual(1, $json['recordsTotal']);
        $this->assertStringContainsString('user@example.com', json_encode($json['data']));
    }
}
