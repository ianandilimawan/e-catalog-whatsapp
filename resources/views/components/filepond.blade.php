@props([
    'name',
    'label' => '',
    'accept' => 'image/*,.webp,.gif,.svg,image/svg+xml',
    'defaultFile' => null,
    'isAvatar' => false,
    'aspectRatio' => null,
    'hint' => null
])

@once
    @push('styles')
        <!-- FilePond & Cropper.js -->
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    @endpush
@endonce

<div class="mb-4" x-data="{
    pond: null,
    showCropper: false,
    cropper: null,
    cropImageSrc: '',
    rawFile: null,
    isDefaultLoading: false,
    initCropper() {
        if (typeof FilePondPluginFileValidateSize !== 'undefined') FilePond.registerPlugin(FilePondPluginFileValidateSize);
        if (typeof FilePondPluginImagePreview !== 'undefined') FilePond.registerPlugin(FilePondPluginImagePreview);

        this.pond = FilePond.create($refs.input, {
            storeAsFile: true,
            allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
            server: null,
            credits: false,
            maxFileSize: '5MB',
            labelMaxFileSizeExceeded: 'Ukuran file terlalu besar',
            labelMaxFileSize: 'Maksimal ukuran file adalah {filesize}',
            imagePreviewHeight: {{ $isAvatar ? 150 : 200 }},
            {!! $isAvatar ? "
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
            " : "" !!}
        });

        @if ($aspectRatio)
            this.pond.on('addfile', (error, file) => {
                if (error || this.isDefaultLoading) return;
                if (file.origin === 1 && !file.getMetadata('cropped')) {
                    this.openCropper(file.file);
                }
            });
        @endif

        @if ($defaultFile) 
            this.isDefaultLoading = true;
            this.pond.addFile('{{ $defaultFile }}')
                .catch(function(e) { console.warn('FilePond preview warning:', e); })
                .finally(() => {
                    setTimeout(() => { this.isDefaultLoading = false; }, 800);
                });
        @endif
    },
    openCropper(file) {
        this.rawFile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            this.cropImageSrc = e.target.result;
            this.showCropper = true;
            this.$nextTick(() => {
                const img = this.$refs.cropperImage;
                if (this.cropper) this.cropper.destroy();
                let numRatio = NaN;
                if ('{{ $aspectRatio }}') {
                    const parts = '{{ $aspectRatio }}'.split(':');
                    if (parts.length === 2) numRatio = parseFloat(parts[0]) / parseFloat(parts[1]);
                }
                this.cropper = new Cropper(img, {
                    aspectRatio: isNaN(numRatio) ? 3/1 : numRatio,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    background: false
                });
            });
        };
        reader.readAsDataURL(file);
    },
    applyCrop() {
        if (!this.cropper) return;
        const canvas = this.cropper.getCroppedCanvas({
            maxWidth: 2000,
            maxHeight: 2000
        });
        canvas.toBlob((blob) => {
            const fileName = (this.rawFile && this.rawFile.name) ? this.rawFile.name : 'cropped-image.jpg';
            const mimeType = blob.type || 'image/jpeg';
            const croppedFile = new File([blob], fileName, { type: mimeType });
            
            // Sync to native file input via DataTransfer
            try {
                const container = new DataTransfer();
                container.items.add(croppedFile);
                this.$refs.input.files = container.files;
            } catch (err) {
                console.warn('DataTransfer sync notice:', err);
            }

            this.pond.removeFiles();
            const pondFile = this.pond.addFile(croppedFile);
            pondFile.then(item => {
                if (item) item.setMetadata('cropped', true);
            });

            this.closeCropper();
        }, 'image/jpeg', 0.9);
    },
    closeCropper() {
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
        this.showCropper = false;
    }
}" x-init="initCropper()">
    @if ($label)
        <div class="flex items-center justify-between mb-2">
            <label for="{{ $name }}" class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">
                {{ $label }}
            </label>
            <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full">Max 5MB</span>
        </div>
    @endif

    <div class="filepond-wrapper {{ $isAvatar ? 'w-40' : '' }}">
        <input type="file" x-ref="input" name="{{ $name }}" id="{{ $name }}"
            accept="{{ $accept }}" {{ $attributes }}>
    </div>

    <div class="flex items-center justify-between mt-1.5">
        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
            <i class="fa fa-info-circle text-blue-500"></i>
            <span>{{ $hint ?? 'Maksimal ukuran gambar 5MB' }}</span>
        </p>

        @if ($aspectRatio)
            <button type="button" @click="
                let files = pond.getFiles();
                if (files.length > 0 && files[0].file) {
                    openCropper(files[0].file);
                } else {
                    $refs.input.click();
                }
            " class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold hover:underline flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L7.121 7.121m5.758 4.879L7 17"></path></svg>
                <span>Atur Posisi / Crop</span>
            </button>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror

    <!-- Modal Cropper -->
    <template x-teleport="body">
        <div x-show="showCropper" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-6 bg-black/80 backdrop-blur-md" @keydown.escape.window="closeCropper()">
            <div class="bg-white dark:bg-gray-900 rounded-3xl max-w-5xl w-full p-6 md:p-8 shadow-2xl border border-gray-100 dark:border-gray-800 flex flex-col max-h-[92vh]">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L7.121 7.121m5.758 4.879L7 17"></path></svg>
                            Atur Posisi & Potongan Gambar
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Geser, perbesar (zoom), atau pilih posisi gambar sesuai rasio yang diinginkan.</p>
                    </div>
                    <button type="button" @click="closeCropper()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="my-4 flex-1 overflow-hidden min-h-[380px] md:min-h-[520px] max-h-[65vh] bg-gray-950 rounded-2xl flex items-center justify-center relative shadow-inner">
                    <img x-ref="cropperImage" :src="cropImageSrc" class="max-w-full max-h-full block" alt="Crop Target">
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <i class="fa fa-info-circle mr-1"></i> Geser gambar atau scroll mouse untuk Zoom
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="closeCropper()" class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="button" @click="applyCrop()" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Gunakan Posisi Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    /* Custom FilePond styles for modern UI */
    .filepond--root {
        background-color: #f9fafb; /* bg-gray-50 */
        border: 2px dashed #e5e7eb; /* border-gray-200 */
        border-radius: 0.75rem; /* rounded-xl */
        transition: all 0.2s ease;
        margin-bottom: 0;
        font-family: inherit;
        overflow: hidden;
    }

    .filepond--root:hover {
        border-color: #d1d5db; /* border-gray-300 */
    }

    .dark .filepond--root {
        background-color: rgba(31, 41, 55, 0.8); /* bg-gray-800/80 */
        border: 2px dashed #374151; /* border-gray-700 */
    }

    .dark .filepond--root:hover {
        border-color: #4b5563; /* border-gray-600 */
    }

    /* Make internal panel transparent so our root styling shows through */
    .filepond--panel-root {
        background-color: transparent !important;
        border: none !important;
    }

    .filepond--root[data-style-panel-layout~='circle'] {
        border-radius: 50% !important;
    }

    .filepond--drop-label {
        color: #6b7280 !important; /* gray-500 */
    }

    .dark .filepond--drop-label {
        color: #9ca3af !important; /* gray-400 */
    }
</style>
