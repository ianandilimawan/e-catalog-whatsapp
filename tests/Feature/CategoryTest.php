<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Http\Controllers\CategoryController;
use App\Models\User;
use App\Models\Role;

class CategoryTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.categories.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.categories.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new category.
     */
    public function test_store_creates_new_category(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.categories.store'), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.categories.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays category details.
     */
    public function test_show_page_displays_category_details(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.categories.show', $category));

        $response->assertStatus(200);
        $response->assertViewHas('category');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewHas('category');
    }

    /**
     * Test update method updates category.
     */
    public function test_update_modifies_category(): void
    {
        $category = Category::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.categories.update', $category), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', array_merge(
            ['id' => $category->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.categories.update', $category), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes category.
     */
    public function test_destroy_deletes_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        // Category uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.categories.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Get valid data for creating a category.
     */
    protected function getValidCreateData(): array
    {
        return [
            'store_id' => 'Test Value',
            'name' => 'Test Category',
            'slug' => 'category-test',
        ];
    }

    /**
     * Get valid data for updating a category.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'store_id' => 'Updated Test Value',
            'name' => 'Updated Test Category',
            'slug' => 'updated category-updated',
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
