<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Store;
use App\Http\Controllers\StoreController;
use App\Models\User;
use App\Models\Role;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full system access',
            'is_active' => true,
        ]);

        // Create a test user for authentication with admin role
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign admin role to user
        $this->user->roles()->attach($adminRole->id);
    }

    /**
     * Test index page is accessible.
     */
    public function test_index_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.stores.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.stores.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new store.
     */
    public function test_store_creates_new_store(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.stores.store'), $data);

        $response->assertRedirect(route('admin.stores.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('stores', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.stores.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays store details.
     */
    public function test_show_page_displays_store_details(): void
    {
        $store = Store::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.stores.show', $store));

        $response->assertStatus(200);
        $response->assertViewHas('store');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $store = Store::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.stores.edit', $store));

        $response->assertStatus(200);
        $response->assertViewHas('store');
    }

    /**
     * Test update method updates store.
     */
    public function test_update_modifies_store(): void
    {
        $store = Store::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.stores.update', $store), $data);

        $response->assertRedirect(route('admin.stores.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('stores', array_merge(
            ['id' => $store->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $store = Store::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.stores.update', $store), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes store.
     */
    public function test_destroy_deletes_store(): void
    {
        $store = Store::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.stores.destroy', $store));

        $response->assertRedirect(route('admin.stores.index'));
        $response->assertSessionHas('success');

        // Store uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('stores', ['id' => $store->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.stores.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Get valid data for creating a store.
     */
    protected function getValidCreateData(): array
    {
        return [
            'user_id' => 'Test Value',
            'name' => 'Test Store',
            'slug' => 'store-test',
            'wa_number' => 'Test Value',
            'theme_color' => 'Test Value',
            'welcome_message' => 'Test description',
            'logo' => 'Test Value',
            'banner' => 'Test Value',
            'button_rounded' => false,
            'dark_mode' => false,
        ];
    }

    /**
     * Get valid data for updating a store.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'user_id' => 'Updated Test Value',
            'name' => 'Updated Test Store',
            'slug' => 'updated store-updated',
            'wa_number' => 'Updated Test Value',
            'theme_color' => 'Updated Test Value',
            'welcome_message' => 'Updated Test description',
            'logo' => 'Updated Test Value',
            'banner' => 'Updated Test Value',
            'button_rounded' => true,
            'dark_mode' => true,
        ];
    }

    /**
     * Get data for database assertion (excluding non-database fields).
     */
    protected function getDatabaseAssertionData(array $data): array
    {
        // Remove fields that are not stored in database (e.g., password confirmation)
        $excludedFields = ['password_confirmation', '_token', '_method'];

        return array_filter($data, function ($key) use ($excludedFields) {
            return !in_array($key, $excludedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
}
