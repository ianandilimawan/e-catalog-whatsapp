<?php

namespace App\Livewire\Tables;

use App\Models\Product;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\Support\Facades\Schema;

class ProductTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'product-table';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';

    public function setUp(): array
    {
        if (auth()->user() && auth()->user()->hasPermission('delete-products')) {
            $this->showCheckBox();
        }

        return [
            PowerGrid::exportable('export_products_' . now()->format('Ymd_His'))
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns()
                ->includeViewOnTop('components.admin.bulk-action-button'),
            PowerGrid::footer()
                ->showPerPage(10, [10, 25, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $query = Product::query()->with('category');

        if (auth()->user() && auth()->user()->hasRole('admin-toko') && !auth()->user()->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            $query->whereHas('store', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        if (Schema::hasColumn('products', 'sort')) {
            $query->orderBy('sort', 'asc');
        }

        return $query;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('category_name', function (Product $row) {
                return $row->category ? $row->category->name : '-';
            })
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('price')
            ->add('action', function (Product $row) {
                $actions = '<div class="flex items-center justify-center gap-1">';

                if (auth()->user() && auth()->user()->hasPermission('view-products')) {
                    $viewUrl = route('admin.products.show', $row->id);
                    $actions .= '<a href="' . $viewUrl . '" class="p-1.5 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('edit-products')) {
                    $editUrl = route('admin.products.edit', $row->id);
                    $actions .= '<a href="' . $editUrl . '" class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                }

                if (auth()->user() && auth()->user()->hasPermission('delete-products')) {
                    $deleteUrl = route('admin.products.destroy', $row->id);
                    $actions .= '<button onclick="window.dispatchEvent(new CustomEvent(\'open-delete-modal\', { detail: { action: \'' . $deleteUrl . '\' } }))" class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>';
                }

                $actions .= '</div>';
                return $actions;
            });
    }

    public function columns(): array
    {
        return [
            Column::add()->title('No')->index()
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center'),

            Column::make('Category', 'category_name'),
            Column::make('Name', 'name')->sortable()->searchable(),
            Column::make('Slug', 'slug')->sortable()->searchable(),
            Column::make('Description', 'description')->sortable()->searchable(),
            Column::make('Price', 'price')->sortable()->searchable(),
            Column::make('Actions', 'action')
                ->headerAttribute('text-center')
                ->bodyAttribute('text-center')
                ->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('triggerBulkDelete')]
    public function triggerBulkDelete(?array $ids = null): void
    {
        if (!$ids) {
            $ids = $this->checkboxValues;
        }

        if (empty($ids)) return;

        // Ensure user has permission
        // if (!auth()->user()->hasPermission('delete-{{modelName}}')) {
        //     $this->dispatch('notify', type: 'error', message: 'You do not have permission to delete {{modelName}}s.');
        //     return;
        // }

        $this->dispatch('confirm-bulk-delete', [
            'ids' => $ids,
            'model' => 'App\\\\Models\\\\Product',
            'refreshRoute' => 'refreshDatatable'
        ]);
    }

    #[\Livewire\Attributes\On('bulkDeleteConfirmed')]
    public function bulkDeleteConfirmed($ids, $model): void
    {
        // TODO: Ensure user has permission
        // if (!auth()->user()->hasPermission('delete-{{modelName}}')) return;

        try {
            Product::whereIn('id', $ids)->delete();
            ActivityLogService::logBulkDelete(Product::class, count($ids), $ids);

            $this->js('window.pgBulkActions.clearAll()');
            $this->dispatch('notify', type: 'success', message: count($ids) . ' {{modelName}}s have been deleted.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to delete {{modelName}}s.');
        }
    }
}
