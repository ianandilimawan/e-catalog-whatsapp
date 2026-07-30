@if(auth()->user()->hasRole('admin'))
    <x-select name="store_id" label="Store id" value="{{ $category->store_id ?? '' }}" :options="$stores" />
@endif

<x-input-floating type="text" name="name" label="Name" value="{{ $category->name ?? '' }}" />

<x-input-floating type="text" name="slug" label="Slug" value="{{ $category->slug ?? '' }}" />


@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = true;
        $hasSlugField = true;
        $slugSourceField = 'name';
        $tagifyFields = array (
);
        $textareaFields = array (
);
        $selectFields = array (
  0 => 'store_id',
);
        $currencyFields = array (
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush