<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Run the migrations
        $this->artisan('migrate');

        // Create admin user
        $this->admin = User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password'),'role'=>'admin']);
    }

    public function test_admin_can_create_category_and_product_with_category()
    {
        $this->actingAs($this->admin);

        // Create category
        $resp = $this->postJson('/admin/categories', ['name'=>'TestCat','description'=>'desc','status'=>'active']);
        $resp->assertStatus(200);
        $this->assertDatabaseHas('categories',['name'=>'TestCat']);

        $cat = Category::where('name','TestCat')->first();
        $this->assertNotNull($cat);

        // Create mitra for product (simple user+mitra)
        $user = User::create(['name'=>'MitraUser','email'=>'mitra@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        $mitra = \App\Models\Mitra::create(['user_id'=>$user->id, 'name'=>'MitraTest','address'=>'addr']);

        // Create product with category
        $productData = ['mitra_id'=>$mitra->id,'category_id'=>$cat->id,'name'=>'Prod1','price'=>10000,'stock'=>10];
        $prodResp = $this->postJson('/admin/products',$productData);
        $prodResp->assertStatus(200);
        $this->assertDatabaseHas('products',['name'=>'Prod1','category_id'=>$cat->id]);
    }

    public function test_admin_can_create_banner()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->image('banner.jpg');
        $resp = $this->postJson('/admin/banners', ['title'=>'B1','link'=>'http://example.com','status'=>'active','image'=>$file]);
        $resp->assertStatus(200);
        $this->assertDatabaseHas('banners',['title'=>'B1']);
    }

    public function test_admin_can_create_mitra_with_bank_fields()
    {
        $this->actingAs($this->admin);
        $user = User::create(['name'=>'MitraUser2','email'=>'mitra2@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        $payload = ['user_id'=>$user->id,'name'=>'MitraBank','business_name'=>'Usaha','bank_account_number'=>'123456','bank_name'=>'Bank A','bank_account_name'=>'John Doe','address'=>'addr'];
        $resp = $this->postJson('/admin/mitras',$payload);
        $resp->assertStatus(200);
        $this->assertDatabaseHas('mitras', ['name'=>'MitraBank','bank_account_number'=>'123456']);
    }

    public function test_admin_can_create_driver_with_bank_fields()
    {
        $this->actingAs($this->admin);
        $user = User::create(['name'=>'DriverUser','email'=>'driver@example.com','password'=>bcrypt('password'),'role'=>'driver']);
        $payload = ['user_id'=>$user->id,'name'=>'DriverBank','wa_contact'=>'628123','bank_account_number'=>'654321','bank_name'=>'Bank B','bank_account_name'=>'Jane Doe'];
        $resp = $this->postJson('/admin/drivers',$payload);
        $resp->assertStatus(200);
        $this->assertDatabaseHas('drivers', ['name'=>'DriverBank','bank_account_number'=>'654321']);
    }

    public function test_admin_can_upload_product_image()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $user = User::create(['name'=>'MitraUser3','email'=>'mitra3@example.com','password'=>bcrypt('password'),'role'=>'mitra']);
        $mitra = \App\Models\Mitra::create(['user_id'=>$user->id,'name'=>'MitraPic','address'=>'addr']);

        $file = UploadedFile::fake()->image('prod.jpg');
        $payload = ['mitra_id'=>$mitra->id,'name'=>'ProdImage','price'=>10000,'stock'=>5,'image'=>$file];

        $resp = $this->post('/admin/products', $payload);
        $resp->assertStatus(200);

        $prod = \App\Models\Product::where('name','ProdImage')->first();
        $this->assertNotNull($prod);
        $this->assertNotNull($prod->image);
        Storage::disk('public')->assertExists('products/'.$prod->image);
    }
}
