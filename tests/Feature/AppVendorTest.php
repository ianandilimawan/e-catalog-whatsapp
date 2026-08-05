<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class AppVendorTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $store;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'vendor@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->store = Store::create([
            'user_id' => $this->user->id,
            'name' => 'Toko Vendor Test',
            'slug' => 'toko-vendor-test',
            'wa_number' => '628123456789',
            'theme_color' => '#16a34a',
            'button_rounded' => true,
            'dark_mode' => false,
        ]);

        $this->category = Category::create([
            'store_id' => $this->store->id,
            'name' => 'Minuman',
            'slug' => 'minuman',
        ]);
    }

    public function test_vendor_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)->get(route('app.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Toko Vendor Test');
    }

    public function test_vendor_can_access_product_list()
    {
        $response = $this->actingAs($this->user)->get(route('app.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Produk');
    }

    public function test_vendor_can_access_product_create()
    {
        $response = $this->actingAs($this->user)->get(route('app.products.create'));
        $response->assertStatus(200);
        $response->assertSee('Tambah Produk');
    }

    public function test_vendor_can_access_store_settings()
    {
        $response = $this->actingAs($this->user)->get(route('app.store.edit'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Toko');
    }

    public function test_vendor_can_update_store_settings()
    {
        $response = $this->actingAs($this->user)->put(route('app.store.update'), [
            'name' => 'Toko Baru Updated',
            'wa_number' => '628987654321',
            'welcome_message' => 'Halo dari toko updated!',
            'theme_color' => '#2563eb',
            'button_rounded' => '1',
        ]);

        $response->assertRedirect(route('app.store.edit'));
        $this->assertDatabaseHas('stores', [
            'id' => $this->store->id,
            'name' => 'Toko Baru Updated',
            'wa_number' => '628987654321',
            'theme_color' => '#2563eb',
        ]);
    }

    public function test_vendor_can_access_stats()
    {
        $response = $this->actingAs($this->user)->get(route('app.stats.index'));
        $response->assertStatus(200);
        $response->assertSee('Statistik Toko');
    }

    public function test_vendor_can_access_account()
    {
        $response = $this->actingAs($this->user)->get(route('app.account.index'));
        $response->assertStatus(200);
        $response->assertSee('vendor@test.com');
    }

    public function test_vendor_can_access_account_edit()
    {
        $response = $this->actingAs($this->user)->get(route('app.account.edit'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Akun');
    }

    public function test_vendor_can_update_profile()
    {
        $response = $this->actingAs($this->user)->put(route('app.account.update_profile'), [
            'name' => 'Vendor Nama Baru',
            'email' => 'vendorbaru@test.com',
        ]);

        $response->assertRedirect(route('app.account.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Vendor Nama Baru',
            'email' => 'vendorbaru@test.com',
        ]);
    }

    public function test_vendor_can_update_password()
    {
        $response = $this->actingAs($this->user)->put(route('app.account.update_password'), [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('app.account.edit'));
        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }

    public function test_vendor_can_update_tracking_and_seo_settings()
    {
        $response = $this->actingAs($this->user)->put(route('app.store.update'), [
            'name' => 'Toko Vendor Test',
            'wa_number' => '628123456789',
            'google_analytics_id' => 'G-ABC123XYZ4',
            'meta_pixel_id' => '123456789012345',
            'google_search_console_code' => 'gsc_verification_code_123',
            'seo_title' => 'Toko Vendor Keren Bandung',
            'seo_description' => 'Jual produk keren berkualitas tinggi.',
            'cta_button_text' => 'Pesan Sekarang',
        ]);

        $response->assertRedirect(route('app.store.edit'));
        $this->assertDatabaseHas('stores', [
            'id' => $this->store->id,
            'google_analytics_id' => 'G-ABC123XYZ4',
            'meta_pixel_id' => '123456789012345',
            'google_search_console_code' => 'gsc_verification_code_123',
            'seo_title' => 'Toko Vendor Keren Bandung',
            'seo_description' => 'Jual produk keren berkualitas tinggi.',
            'cta_button_text' => 'Pesan Sekarang',
        ]);
    }
}
