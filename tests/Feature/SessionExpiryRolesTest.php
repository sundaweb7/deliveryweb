<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\LoginHistory;

class SessionExpiryRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_mitra_is_logged_out_if_login_over_one_week_ago_without_recent_login()
    {
        $user = User::create(['name'=>'MitraUser','email'=>'mitra@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        // create mitra profile if necessary
        \App\Models\Mitra::create(['user_id'=>$user->id,'name'=>'MitraTest','address'=>'addr']);

        $this->actingAs($user)
            ->withSession(['login_at' => now()->subWeeks(2)->timestamp])
            ->get('/mitra')
            ->assertRedirect(route('mitra.login'));
    }

    public function test_mitra_is_allowed_if_recent_login_history_exists()
    {
        $user = User::create(['name'=>'MitraUser2','email'=>'mitra2@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        \App\Models\Mitra::create(['user_id'=>$user->id,'name'=>'MitraTest2','address'=>'addr']);

        LoginHistory::create(['user_id'=>$user->id,'role'=>'mitra','ip'=>'127.0.0.1']);

        $this->actingAs($user)
            ->withSession(['login_at' => now()->subWeeks(2)->timestamp])
            ->get('/mitra')
            ->assertStatus(200);
    }

    public function test_driver_is_logged_out_if_login_over_one_week_ago_without_recent_login()
    {
        $user = User::create(['name'=>'DriverUser','email'=>'driver@example.com','password'=>bcrypt('password'),'role'=>'driver']);
        \App\Models\Driver::create(['user_id'=>$user->id,'name'=>'DriverTest']);

        $this->actingAs($user)
            ->withSession(['login_at' => now()->subWeeks(2)->timestamp])
            ->get('/driver')
            ->assertRedirect(route('driver.login'));
    }

    public function test_driver_is_allowed_if_recent_login_history_exists()
    {
        $user = User::create(['name'=>'DriverUser2','email'=>'driver2@example.com','password'=>bcrypt('password'),'role'=>'driver']);
        \App\Models\Driver::create(['user_id'=>$user->id,'name'=>'DriverTest2']);

        LoginHistory::create(['user_id'=>$user->id,'role'=>'driver','ip'=>'127.0.0.1']);

        $this->actingAs($user)
            ->withSession(['login_at' => now()->subWeeks(2)->timestamp])
            ->get('/driver')
            ->assertStatus(200);
    }
}
