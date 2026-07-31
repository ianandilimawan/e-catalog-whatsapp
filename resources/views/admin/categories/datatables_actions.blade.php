@if(auth()->user() && auth()->user()->hasPermission('view-categories'))
<a href="{{ route('admin.categories.show', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('edit-categories'))
<a href="{{ route('admin.categories.edit', $id) }}"
    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">Edit</a>
@endif
@if(auth()->user() && auth()->user()->hasPermission('delete-categories'))
<button @click="$dispatch('open-delete-modal', { action: '{{ route('admin.categories.destroy', $id) }}' })"
    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
@endif
