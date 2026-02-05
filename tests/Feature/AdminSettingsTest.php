<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->admin = User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'role'=>'admin']);
    }

    public function test_admin_can_update_price_and_wa_settings()
    {
        $this->actingAs($this->admin);

        $payload = [
            'price_per_km' => 5000,
            'admin_fee_percent' => 12,
            'max_radius_km' => 25,
            'mpedia_api_key' => 'TESTKEY123',
            'mpedia_sender' => 'SENDERID',

            'duitku_merchant_code' => 'MRC123',
            'duitku_merchant_key' => 'MK123',
            'duitku_api_url' => 'https://api.duitku.example/',
            'duitku_is_test_mode' => '1',
        ];

        $resp = $this->post('/admin/settings', $payload);
        $resp->assertRedirect();

        $this->assertDatabaseHas('settings', ['key'=>'price_per_km','value'=>'5000']);
        $this->assertDatabaseHas('settings', ['key'=>'admin_fee_percent','value'=>'12']);
        $this->assertDatabaseHas('settings', ['key'=>'max_radius_km','value'=>'25']);
        $this->assertDatabaseHas('settings', ['key'=>'mpedia_api_key','value'=>'TESTKEY123']);
        $this->assertDatabaseHas('settings', ['key'=>'mpedia_sender','value'=>'SENDERID']);

        // Duitku asserts
        $this->assertDatabaseHas('settings', ['key'=>'duitku_merchant_code','value'=>'MRC123']);
        $this->assertDatabaseHas('settings', ['key'=>'duitku_merchant_key','value'=>'MK123']);
        $this->assertDatabaseHas('settings', ['key'=>'duitku_api_url','value'=>'https://api.duitku.example/']);
        $this->assertDatabaseHas('settings', ['key'=>'duitku_is_test_mode','value'=>'1']);
    }
}
