# Laravel CRUD Generator

A modern Laravel CRUD generator similar to webcore but updated for Laravel 12 with Tailwind CSS support.

**Unified Command**: All functionality is accessible via `generate:scaffold` command!

## Features

-   Generate complete CRUD operations with a single command
-   **Interactive mode** - Enter fields one by one with step-by-step prompts
-   Support for various field types (text, textarea, select, checkbox, date, email, password, number, file)
-   Modern Tailwind CSS templates
-   **Livewire PowerGrid integration** - Highly reactive, modern tables with server-side processing
-   JSON schema support for complex field definitions
-   Validation rules generation
-   Optional migration generation (use `--migration` flag)
-   Model, Controller, Request, View, and PowerGrid Table generation
-   **Automatic unit test generation** (enabled by default)
-   Route registration
-   **Highly Optimized**: Compiles views statically (no runtime loops) and injects modular traits (e.g. `HasFileUpload`) for thin and readable controllers.
-   **Security**: Automatically injects Spatie Laravel Permission middleware directly into controllers.
-   **Native Enum Support (PHP 8.1+)**: Generates Enums, validation rules (`Rule::enum`), and frontend `<x-select>` elements natively.
-   **BelongsTo Relationships**: Generates `$this->belongsTo()` on models and auto-injects foreign data into views.
-   **Soft Deletes**: Automatically injects `SoftDeletes` traits and migrations via the `--soft-deletes` flag.

## What's New in v2.1 (Core Refactor)

This version introduces a massive refactor to the generator architecture, making it highly modular and maintainable for your team:
- **Modular Generators**: The bloated `ViewGenerator` (which was previously over 46KB) has been broken down into single-responsibility components: `FormFieldRenderer`, `TableRenderer`, `ComponentRenderer`, and `ImportExportRenderer`.
- **Livewire PowerGrid**: Replaced the legacy Livewire PowerGrid with the highly reactive **Livewire PowerGrid**. The old `DataTableGenerator` has been replaced by `PowerGridTableGenerator`.
- **Admin View Organization**: Generated views are now cleanly placed inside `resources/views/admin/{module_name}` to separate them from public-facing views.
- **Javascript Decoupling**: Large inline Javascript chunks (for password strength, filepond, Dropify, tagify, slug generation) have been extracted to their own files inside `resources/stubs/js/` and are included cleanly via Vite/Blade directives.
- **Controller Thinning**: Repetitive logic like File Uploads and Import/Export logic are now injected dynamically via modular traits (`HasFileUpload`, `HasImportExport`) instead of cluttering every Controller.
- **Selective Scaffolding**: Added new `--only=...` and `--except=...` flags so you can generate exactly what you need (e.g. `--only=model,migration`).

## Built-in Admin Features

Beyond the CRUD Generator, this template comes pre-packaged with a suite of enterprise-grade features that you can control directly from the Admin Panel's **Settings** menu:

- **Dynamic Theme & Appearance:**
  - Let users or administrators choose between **Light Mode**, **Dark Mode**, or **System Default**.
  - Theme choices are saved persistently to the database and sync seamlessly across the guest pages (like Login) and the main admin dashboard without screen flickering.
  - Dynamically upload and swap the Application Logo via the UI.

- **Dynamic SMTP Configuration:**
  - No need to hardcode email credentials in your `.env` file for production.
  - Configure SMTP Host, Port, Username, Password, and Encryption directly from the Admin Settings UI.
  - The application automatically intercepts and uses these settings whenever dispatching emails.

- **Advanced Two-Factor OTP Login:**
  - Toggleable via the `.env` file (`ENABLE_OTP_LOGIN=true`).
  - Instead of standard password-only login, users receive a secure 6-digit OTP code to their email.
  - **Spam Protection:** "Resend OTP" button features a 30-second JavaScript countdown stored in browser session to prevent abuse.
  - **Efficient Data Handling:** OTPs expire securely after 5 minutes using Laravel Cache (no database bloat). Re-requesting an OTP within 5 minutes safely resends the existing active code.

- **Elegant Maintenance Mode:**
  - Flip a switch in the Settings menu to activate Maintenance Mode.
  - All standard users will be locked out and presented with a highly attractive, fully-responsive "Under Maintenance" 3D splash page featuring an abstract background.
  - Administrators are intelligently bypassed based on IP allow-listing, allowing developers to continue working unhindered.

- **Centralized Toast Notifications:**
  - Clean `<x-toast>` Blade components handle all session success, error, and warning flashes automatically.
  - Replaces bloated HTML alerts across all stubs and views.

## Installation

### Prerequisites

Before installing, make sure you have the following installed on your system:

-   **PHP** 8.2 or higher
-   **Composer** (PHP dependency manager)
-   **Node.js** 18+ and **npm** (for frontend assets)
-   **Database** (MySQL, PostgreSQL, or SQLite)
-   **Git** (optional, for cloning the repository)

### Step 1: Clone or Download the Project

```bash
# Clone the repository
git clone <repository-url>
cd admin-template

# Or download and extract the project files
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages including Laravel framework and Livewire PowerGrid.

### Step 3: Install Node.js Dependencies

```bash
npm install
```

This will install Tailwind CSS, Vite, and other frontend dependencies.

### Step 4: Environment Configuration

Create a `.env` file from the example (if available) or create one manually:

```bash
# If .env.example exists
cp .env.example .env

# Or create .env manually
```

Configure your database and application settings in the `.env` file:

```env
APP_NAME="Laravel Admin Template"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

This will generate a unique application encryption key.

### Step 6: Database Setup

Create your database (if not already created):

```bash
# For MySQL
mysql -u root -p
CREATE DATABASE your_database_name;

# For PostgreSQL
createdb your_database_name

# For SQLite (creates database/database.sqlite automatically)
```

### Step 7: Run Migrations

```bash
php artisan migrate
```

This will create all necessary database tables.

### Step 8: Seed Database (Optional)

If you have seeders, run them to populate initial data:

```bash
php artisan db:seed
```

### Step 9: Build Frontend Assets

```bash
# For development
npm run dev

# For production
npm run build
```

### Step 10: Start Development Server

```bash
# Option 1: Using Laravel's built-in server
php artisan serve

# Option 2: Using the dev script (includes queue, logs, and vite)
composer run dev
```

The application will be available at `http://localhost:8000` (or the port specified).

### Quick Setup (All-in-One)

If you want to set up everything at once, you can use the setup script:

```bash
composer run setup
```

This will:

-   Install Composer dependencies
-   Create `.env` file (if it doesn't exist)
-   Generate application key
-   Run migrations
-   Install npm dependencies
-   Build frontend assets

### Troubleshooting

**Permission Issues:**

```bash
# Fix storage and cache permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**Clear Cache:**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

**Reinstall Dependencies:**

```bash
# Remove vendor and node_modules
rm -rf vendor node_modules

# Reinstall
composer install
npm install
```

### Docker Installation (Alternative)

If you prefer using Docker, you can use the provided Dockerfile:

```bash
# Build the image
docker build -t admin-template .

# Run the container
docker run -p 80:80 admin-template
```

---

**Note:** The CRUD generator is already integrated into this Laravel project. No additional installation is required for the generator itself.

## Usage

### Quick Start

**Interactive Mode (Easiest - Recommended for beginners):**

```bash
# Start interactive mode - will prompt for model name and fields one by one
php artisan generate:scaffold

# Or with model name - will prompt for fields only
php artisan generate:scaffold {ModelName}
```

**From Existing Database Table:**

```bash
php artisan generate:scaffold {ModelName} --fromTable --tableName={table_name}
```

**From Field Definitions:**

```bash
php artisan generate:scaffold {ModelName} --fields="field1:type:htmlType:options"
```

**From JSON Schema:**

```bash
php artisan generate:scaffold {ModelName} --schema=path/to/schema.json
```

Replace `{ModelName}` and placeholders with your actual values.

---

### Generate from Existing Database Table

Generate CRUD from an existing database table:

```bash
php artisan generate:scaffold Blog --fromTable --tableName=blog
```

**Format:**

```bash
php artisan generate:scaffold {ModelName} --fromTable --tableName={table_name}
```

**Example with MySQL:**

```bash
# 1. Update .env with your database credentials
DB_CONNECTION=mysql
DB_HOST=your_database_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

# 2. Connect to your database and generate from existing tables
php artisan generate:scaffold Blog --fromTable --tableName=blog
php artisan generate:scaffold Post --fromTable --tableName=posts
php artisan generate:scaffold Category --fromTable --tableName=categories

# Replace {ModelName} and {table_name} with your actual model and table names
```

**Example with SQLite:**

```bash
# SQLite uses .env or database/database.sqlite
DB_CONNECTION=sqlite
# DB_DATABASE is optional for SQLite

# Generate from existing SQLite tables
php artisan generate:scaffold User --fromTable --tableName=users
```

**Note:**

-   Replace placeholders with your actual database credentials
-   The `{ModelName}` will be used for generated class names (Blog, Post, etc.)
-   The `{table_name}` should match your actual database table name
-   Works with MySQL, PostgreSQL, and SQLite

This will:

-   Read the table structure from the database
-   Automatically detect column types (int, varchar, text, json, date, etc.)
-   **Detect ENUM fields and generate select dropdowns with proper options**
-   Generate Model with proper fillable and casts
-   Generate Controller, Request, Views
-   **Skip migration generation by default** (use `--migration` flag to generate migration)
-   Skip Menu/Permissions if tables don't exist

**Migration Generation:**

Migration files are **not generated by default**. To generate a migration file, use the `--migration` flag:

```bash
# Generate migration from existing table
php artisan generate:scaffold Blog --fromTable --tableName=blog --migration

# Generate migration from fields
php artisan generate:scaffold Product --fields="name:string:text:required" --migration
```

This is useful when you want to:

-   Document existing table structure with a migration file
-   Create migrations for tables that were created manually
-   Keep your migrations up-to-date with database schema

**ENUM Support:**
The generator fully supports PHP 8.1+ Native Enums! You can generate an Enum class, apply it in your database migration (as string or enum), and cast it in your model.
- Format: `status:string:enum=App\Enums\StatusEnum(draft,published)`
- Automatically generates the Enum file at `app/Enums/StatusEnum.php`
- Uses `Rule::enum()` in the FormRequest validation.
- Automatically maps Enum cases to dropdown options in `<x-select>`.

**BelongsTo Relations:**
The generator supports defining BelongsTo relationships directly from the command.
- Format: `category_id:foreignId:belongsTo(Category)`
- Automatically adds the `belongsTo()` relationship method to the Model.
- Automatically queries the related Model in the Controller (`Category::pluck('name', 'id')`) and injects it into the view.
- Automatically generates `<x-select>` mapped to the relationship data.

**Soft Deletes:**
You can add the `--soft-deletes` flag to the command.
- Automatically adds `$table->softDeletes()` to the generated Migration.
- Automatically adds `use SoftDeletes` trait to the Eloquent Model.

**Rich Text Editor:**
For `text` and `textarea` fields, the generator automatically includes:

-   TinyMCE rich text editor
-   Full toolbar with formatting options
-   Image upload and media support
-   Clean, modern interface

**Switch Components:**
For `boolean` fields, the generator creates:

-   Modern toggle switch instead of checkbox
-   Smooth animations and transitions
-   Proper form handling (hidden input for false values)
-   Accessible design with proper labels

**DataTables Integration:**
The generator automatically creates Livewire PowerGrid service classes for AJAX-powered tables:

-   Server-side processing for better performance
-   Automatic column generation from model fields
-   Built-in search, sort, and pagination
-   Clean, reusable code structure
-   DataTable files are created in `app/DataTables/`

**Note:** Make sure to install Livewire PowerGrid package:

```bash
composer require power-components/livewire-powergrid
```

**Currency Input:**
For currency/numeric fields, you can use currency input with automatic formatting:

-   AutoNumeric.js v4.6.0 integration for currency formatting
-   Indonesian Rupiah format (Rp 1.000.000)
-   Auto-format while typing with prefix "Rp "
-   Automatic unformat on form submit (sends numeric value to server)
-   Support for dark mode
-   Automatically handles existing values from database

The `data-currency` attribute automatically enables currency formatting. The prefix "Rp " is automatically added by AutoNumeric.js.

### Generate from Fields

Generate CRUD for a model with field definitions:

```bash
php artisan generate:scaffold {ModelName} --fields="field1:type:htmlType:options,field2:type:htmlType:options"
```

**Example:**

```bash
php artisan generate:scaffold Product --fields="name:string:text:required,description:text:textarea:nullable,price:decimal:number:required"
```

### Interactive Mode (New!)

You can now generate CRUD interactively by entering fields one by one. This is useful when you want to define fields step-by-step without typing everything in one command.

**Start interactive mode:**

```bash
# Without model name - will prompt for model name first
php artisan generate:scaffold

# With model name - will prompt for fields only
php artisan generate:scaffold Product
```

**How it works:**

1. If model name is not provided, you'll be asked to enter it first
2. The command will display format instructions and examples
3. You'll be prompted to enter fields one by one:
    - `Field 1 (or press Enter to finish/continue without fields):`
    - `Field 2 (or press Enter to finish/continue without fields):`
    - And so on...
4. Press Enter with empty input to finish adding fields
5. **You can press Enter immediately** (without entering any field) to generate CRUD with basic structure (id, created_at, updated_at only)
6. The generator will show confirmation after each field is added

**Example interactive session:**

```bash
$ php artisan generate:scaffold Product

Model name (e.g., Product, User, Order): Product
No fields provided. Starting interactive mode...
Format: name:dbType:htmlType:options
Example: name:string:text:nullable
Example: email:string:email:required,email
Example: status:string:select:options=active,inactive

Enter fields one by one. Press Enter with empty input to finish.
You can also press Enter immediately to generate CRUD with basic structure (id, timestamps only).

Field 1 (or press Enter to finish/continue without fields): name:string:text:required
✓ Added field: name (string, text)
Field 2 (or press Enter to finish/continue without fields): description:text:textarea:nullable
✓ Added field: description (text, textarea)
Field 3 (or press Enter to finish/continue without fields): price:decimal:number:required
✓ Added field: price (decimal, number)
Field 4 (or press Enter to finish/continue without fields): status:string:select:options=active,inactive
✓ Added field: status (string, select)
Field 5 (or press Enter to finish/continue without fields): [Enter]

Generating CRUD for Product...
...
```

**Example: Generate CRUD without additional fields:**

```bash
$ php artisan generate:scaffold Log

Model name (e.g., Product, User, Order): Log
No fields provided. Starting interactive mode...
Format: name:dbType:htmlType:options
Example: name:string:text:nullable
Example: email:string:email:required,email
Example: status:string:select:options=active,inactive
Note: You can press Enter without entering any field to generate CRUD with basic structure (id, timestamps only)

Enter fields one by one. Press Enter with empty input to finish.
You can also press Enter immediately to generate CRUD with basic structure (id, timestamps only).

Field 1 (or press Enter to finish/continue without fields): [Enter immediately]

⚠ No additional fields provided. Will generate CRUD with basic structure (id, created_at, updated_at only).

Generating CRUD for Log...
✓ Generated: App\Generators\Generators\ModelGenerator
✓ Generated: App\Generators\Generators\ControllerGenerator
...
```

**What gets generated when fields are empty:**

When you generate CRUD without additional fields, you'll get:

-   ✅ **Model** - With only `id`, `created_at`, and `updated_at` fields
-   ✅ **Controller** - Full CRUD operations (index, create, store, show, edit, update, destroy)
-   ✅ **Views** - Index page with DataTable showing only ID column, create/edit forms with minimal structure
-   ✅ **Requests** - Basic validation (can be customized later)
-   ✅ **Routes** - All resource routes registered
-   ✅ **DataTable** - AJAX-powered table with ID column only
-   ✅ **Tests** - Unit tests for all CRUD operations (if not disabled)

This is useful for:

-   Creating placeholder CRUD that you'll customize later
-   Generating basic structure for models that only need timestamps
-   Quick prototyping without defining fields upfront

**Benefits of interactive mode:**

-   ✅ No need to remember complex field syntax upfront
-   ✅ See confirmation after each field is added
-   ✅ Easy to correct mistakes (invalid format won't increment field number)
-   ✅ Can stop anytime by pressing Enter
-   ✅ **Can generate CRUD with basic structure only** (id, timestamps) by pressing Enter immediately
-   ✅ Great for beginners or when exploring field options

### Generate from JSON Schema

Create a schema file in `resources/schemas/` with your field definitions:

```json
{
    "model": "Product",
    "fields": [
        {
            "name": "name",
            "dbType": "string",
            "htmlType": "text",
            "validation": ["required", "string", "max:255"],
            "searchable": true,
            "sortable": true
        },
        {
            "name": "description",
            "dbType": "text",
            "htmlType": "textarea",
            "validation": ["nullable", "string"]
        },
        {
            "name": "price",
            "dbType": "decimal",
            "htmlType": "number",
            "validation": ["required", "numeric", "min:0"]
        },
        {
            "name": "category",
            "dbType": "string",
            "htmlType": "select",
            "validation": ["required", "string"],
            "options": ["Electronics", "Clothing", "Books", "Home", "Sports"]
        },
        {
            "name": "is_active",
            "dbType": "boolean",
            "htmlType": "checkbox",
            "validation": ["boolean"],
            "default": true
        }
    ]
}
```

Then generate using the schema:

```bash
php artisan generate:scaffold {ModelName} --schema=resources/schemas/your_schema.json
```

**Example:**

```bash
php artisan generate:scaffold Product --schema=resources/schemas/product.json
```

### Field Types

#### Database Types

-   `string` - VARCHAR
-   `text` - TEXT
-   `integer` - INTEGER
-   `decimal` - DECIMAL
-   `boolean` - BOOLEAN
-   `date` - DATE
-   `datetime` - DATETIME
-   `timestamp` - TIMESTAMP
-   `json` - JSON

#### HTML Types

-   `text` - Text input
-   `textarea` - Textarea
-   `select` - Select dropdown
-   `checkbox` - Checkbox
-   `date` - Date input
-   `email` - Email input
-   `password` - Password input
-   `number` - Number input
-   `file` - File input

### Field Options

-   `nullable` - Make field nullable
-   `searchable` - Enable search functionality
-   `sortable` - Enable sorting
-   `required` - Make field required
-   `validation:rule1,rule2` - Custom validation rules
-   `options:option1,option2` - Options for select fields
-   `default:value` - Default value

### Command Options

-   `--migration` - Generate migration file (migration is **not generated by default**)
-   `--no-migration` - Skip migration generation (default behavior)
-   `--no-controller` - Skip controller generation
-   `--no-model` - Skip model generation
-   `--no-views` - Skip views generation
-   `--no-request` - Skip request generation
-   `--no-routes` - Skip routes generation
-   `--no-menu` - Skip menu generation
-   `--no-permissions` - Skip permissions generation
-   `--no-test` - Skip test generation (tests are **generated by default**)
-   `--with-factory` - Generate factory
-   `--with-seeder` - Generate seeder
-   `--with-test` - Generate test (default behavior)
-   `--soft-deletes` - Inject soft deletes trait and migration
-   `--section-title=` - Section title for the menu

### Examples

**Generate using interactive mode:**

```bash
# Start interactive mode
php artisan generate:scaffold

# Or with model name
php artisan generate:scaffold Product

# Then follow the prompts to enter fields one by one
# Field 1: name:string:text:required
# Field 2: price:decimal:number:required
# Field 3: [Enter to finish]
```

**Generate CRUD with basic structure only (no additional fields):**

```bash
# Start interactive mode and press Enter immediately when asked for fields
php artisan generate:scaffold Log

# When prompted for Field 1, just press Enter
# This will generate CRUD with only id, created_at, updated_at
# Useful for creating placeholder CRUD or models that only need timestamps
```

**Generate a simple blog post:**

```bash
php artisan generate:scaffold Post --fields="title:string:text:required,max:255,content:text:textarea:required,status:string:select:required,options:published,draft,featured_at:datetime:date:nullable"
```

**Generate a user model with factory and seeder:**

```bash
php artisan generate:scaffold User --fields="name:string:text:required,email:string:email:required,unique:users,password:string:password:required,min:8,is_admin:boolean:checkbox:default:false" --with-factory --with-seeder
```

**Generate from existing database table (without migration):**

```bash
# For MySQL
php artisan generate:scaffold Blog --fromTable --tableName=blog

# For PostgreSQL
php artisan generate:scaffold Article --fromTable --tableName=articles

# For SQLite
php artisan generate:scaffold Product --fromTable --tableName=products
```

**Generate from existing database table with migration:**

```bash
# Generate migration based on existing table structure
php artisan generate:scaffold Blog --fromTable --tableName=blog --migration

# For PostgreSQL
php artisan generate:scaffold Article --fromTable --tableName=articles --migration

# For SQLite
php artisan generate:scaffold Product --fromTable --tableName=products --migration
```

**Generate only Controller, Model, and Request (skip views and permissions):**

```bash
# Useful when fields are combined into one view or permissions are not needed
php artisan generate:scaffold ModelName --fromTable --tableName=table_name --no-views --no-permissions

# Or with fields
php artisan generate:scaffold ModelName --fields="name:string:text:required,price:integer:number" --no-views --no-permissions
```

**Generate with migration from fields:**

```bash
php artisan generate:scaffold Product --fields="name:string:text:required,price:decimal:number:required" --migration
```

**Generate without test (skip test generation):**

```bash
php artisan generate:scaffold Product --fields="name:string:text:required,price:decimal:number:required" --no-test
```

## Generated Files

The generator creates the following files:

-   **Model**: `app/Models/{ModelName}.php`
-   **Controller**: `app/Http/Controllers/{ModelName}Controller.php`
-   **DataTable**: `app/DataTables/{ModelName}DataTable.php` (automatically generated with controller)
-   **Request**: `app/Http/Requests/{ModelName}Request.php`
-   **Migration**: `database/migrations/{timestamp}_create_{table_name}_table.php` (only if `--migration` flag is used)
-   **Views**: `resources/views/{model_name}s/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, `datatables_actions.blade.php`
-   **Test**: `tests/Feature/{ModelName}Test.php` (generated by default, use `--no-test` to skip)
-   **Routes**: Added to `routes/web.php`

## Unit Testing

The generator automatically creates comprehensive unit tests for all CRUD operations. Tests are generated by default to help minimize errors and ensure code quality.

### Running Tests

**Run all tests:**

```bash
php artisan test
```

**Run tests for a specific model:**

```bash
php artisan test --filter {ModelName}Test
```

**Example:**

```bash
# Run all Product tests
php artisan test --filter ProductTest

# Run all Blog tests
php artisan test --filter BlogTest
```

**Run tests with coverage:**

```bash
php artisan test --coverage
```

**Run a specific test method:**

```bash
php artisan test --filter test_index_page_is_accessible
```

### Generated Test Coverage

The generated tests include:

-   ✅ **Index page** - Tests that the index page is accessible
-   ✅ **Create page** - Tests that the create page is accessible
-   ✅ **Store method** - Tests creating new records with validation
-   ✅ **Show page** - Tests displaying record details
-   ✅ **Edit page** - Tests that the edit page is accessible
-   ✅ **Update method** - Tests updating records with validation
-   ✅ **Destroy method** - Tests deleting records
-   ✅ **Validation** - Tests required field validation
-   ✅ **Authorization** - Tests unauthorized access is denied

### Customizing Tests

After generation, you can customize the test file at `tests/Feature/{ModelName}Test.php`. You'll need to:

1. **Fill in test data** - Update `getValidCreateData()` and `getValidUpdateData()` methods with actual field values
2. **Add custom assertions** - Add additional test cases specific to your model
3. **Update database assertions** - Modify `getDatabaseAssertionData()` if needed

**Example test data:**

```php
protected function getValidCreateData(): array
{
    return [
        'name' => 'Test Product',
        'price' => 100000,
        'description' => 'Test description',
        'status' => 'active',
    ];
}
```

### Skipping Test Generation

To skip test generation, use the `--no-test` flag:

```bash
php artisan generate:scaffold Product --fields="name:string:text:required" --no-test
```

## Customization

### Templates

You can customize the generated templates by modifying the stub files in `resources/stubs/`:

-   `model.stub` - Model template
-   `controller.stub` - Controller template
-   `datatable.stub` - DataTable service class template
-   `request.stub` - Request template
-   `migration.stub` - Migration template
-   `test.stub` - Unit test template
-   `view/index.stub` - Index view template (uses DataTables)
-   `view/create.stub` - Create view template
-   `view/edit.stub` - Edit view template
-   `view/show.stub` - Show view template
-   `view/datatables_actions.stub` - DataTable action buttons template

### Adding New Field Types

To add new field types, modify the `GeneratorField` class in `app/Generators/Common/GeneratorField.php` and add the appropriate HTML generation logic in the `getFormInput()` method.

## Requirements

-   Laravel 12+
-   PHP 8.2+
-   Tailwind CSS (for styling)
-   Livewire PowerGrid (for table functionality)

### Installing Livewire PowerGrid

```bash
composer require power-components/livewire-powergrid
```

After installation, publish the config file (optional):

```bash
php artisan powergrid:publish
```

## License

MIT License
