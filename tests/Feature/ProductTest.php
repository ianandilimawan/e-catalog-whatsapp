<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Models\User;
use App\Models\Role;

class ProductTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.products.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.products.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new product.
     */
    public function test_store_creates_new_product(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays product details.
     */
    public function test_show_page_displays_product_details(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.products.show', $product));

        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    /**
     * Test update method updates product.
     */
    public function test_update_modifies_product(): void
    {
        $product = Product::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', array_merge(
            ['id' => $product->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.products.update', $product), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes product.
     */
    public function test_destroy_deletes_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        // Product uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.products.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Get valid data for creating a product.
     */
    protected function getValidCreateData(): array
    {
        return [
            'store_id' => 'Test Value',
            'category_id' => 'Test Value',
            'name' => 'Test Product',
            'slug' => 'product-test',
            'description' => 'Test description',
            'price' => 1,
            'image' => 'Test Value',
        ];
    }

    /**
     * Get valid data for updating a product.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'store_id' => 'Updated Test Value',
            'category_id' => 'Updated Test Value',
            'name' => 'Updated Test Product',
            'slug' => 'updated product-updated',
            'description' => 'Updated Test description',
            'price' => 2,
            'image' => 'Updated Test Value',
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
