<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app.app')] class extends Component {
    use WithFileUploads;

    public $uploads = [];
    public $search = '';
    public $selectedMedia = null;

    public function updatedUploads()
    {
        $this->uploadImages();
    }

    public function uploadImages()
    {
        if (empty($this->uploads))
            return;

        $this->validate([
            'uploads.*' => 'image|max:10240',
        ]);

        foreach ($this->uploads as $file) {
            $path = $file->store('media', 'public');

            Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'alt_text' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);
        }

        $this->uploads = [];
        $this->dispatch('media-uploaded');
    }

    public function removeUpload($index)
    {
        array_splice($this->uploads, $index, 1);
    }

    public function clearUploads()
    {
        $this->uploads = [];
    }

    public function deleteMedia($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        if ($this->selectedMedia && $this->selectedMedia->id == $id) {
            $this->selectedMedia = null;
        }
    }

    public function selectMedia($id)
    {
        $this->selectedMedia = Media::find($id);
    }

    public function with()
    {
        return [
            'mediaItems' => Media::where('file_name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(24),
        ];
    }
};
?>

<div class="p-6 bg-card rounded-xl min-h-[calc(100vh-10rem)] " x-data="{ 
        isUploading: false, 
        progress: 0, 
        showSuccess: false,
        isDropping: false
    }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false; progress = 0"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress"
    x-on:media-uploaded.window="showSuccess = true; setTimeout(() => showSuccess = false, 3000)">

    {{-- Success Notification --}}
    <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-6 right-6 z-50 bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3"
        x-cloak>
        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-bold">Media uploaded successfully!</span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-foreground">Media Library</h2>
            <p class="text-muted-foreground text-sm">Upload and manage all your website assets in one place.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="relative">
                <input wire:model.live="search" type="text" placeholder="Search files..."
                    class="pl-10 pr-4 py-2 bg-transparent border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                <svg class="size-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <label
                class="flex items-center gap-2 px-4 py-2 bg-blue-900 text-white rounded-lg text-sm font-semibold hover:bg-blue-800 transition-colors cursor-pointer group shadow-sm">
                <svg class="size-4 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload Images
                <input type="file" wire:model="uploads" multiple class="hidden" accept="image/*">
            </label>
        </div>
    </div>

    {{-- Drag & Drop Zone --}}
    <div x-on:dragover.prevent="isDropping = true" x-on:dragleave.prevent="isDropping = false" x-on:drop.prevent="
            isDropping = false;
            const files = $event.dataTransfer.files;
            if (files.length > 0) {
                @this.upload('uploads', files[0]); // Simple case for first file, ideally loop for multiple
                // For multiple files with wire:model, we usually need to handle them carefully in Alpine
                // or use a more advanced upload strategy. But Livewire handles multiple via the property.
                @this.uploadMultiple('uploads', files);
            }
        " :class="isDropping ? 'border-blue-500 bg-blue-50/50 scale-[1.01]' : 'border-gray-200 bg-gray-50/30'"
        class="mb-8 border-2 border-dashed rounded-2xl p-12 transition-all duration-300 flex flex-col items-center justify-center gap-4 group cursor-pointer relative"
        onclick="this.querySelector('input').click()">

        <input type="file" wire:model="uploads" multiple class="hidden" accept="image/*">

        <div
            class="size-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform shadow-inner">
            <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
        </div>
        <div class="text-center">
            <p class="text-lg font-bold text-gray-900">Click or drag images here to upload</p>
            <p class="text-sm text-gray-500">Supports PNG, JPG, GIF up to 10MB</p>
        </div>

        <div x-show="isDropping"
            class="absolute inset-0 bg-blue-500/10 rounded-2xl flex items-center justify-center pointer-events-none"
            x-cloak>
            <span class="bg-blue-600 text-white px-6 py-2 rounded-full font-bold shadow-lg animate-pulse">Drop to
                Upload</span>
        </div>
    </div>

    {{-- Upload Progress --}}
    <div x-show="isUploading" x-cloak class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-50">
                Uploading...
            </span>
            <span class="text-xs font-semibold inline-block text-blue-600" x-text="progress + '%'"></span>
        </div>
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-blue-100">
            <div :style="`width: ${progress}%`"
                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-600 transition-all duration-300">
            </div>
        </div>
    </div>

    {{-- Temporary Upload Previews --}}
    @if ($uploads && false) {{-- Hidden because we now auto-upload, kept here for logic reference if needed --}}
        <div class="mb-8 p-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Processing
                    ({{ count($uploads) }})</h3>
                <div class="flex gap-2">
                    <button wire:click="clearUploads" class="text-xs text-red-600 hover:underline font-medium">Clear
                        All</button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @foreach($mediaItems as $item)
            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer transition-all hover:shadow-lg hover:border-blue-300 {{ $selectedMedia && $selectedMedia->id == $item->id ? 'ring-4 ring-blue-500 ring-offset-2' : '' }}"
                wire:click="selectMedia({{ $item->id }})">

                <img src="{{ Storage::url($item->file_path) }}" alt="{{ $item->alt_text }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button wire:click.stop="deleteMedia({{ $item->id }})"
                        class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    <button class="p-2 bg-white text-gray-800 rounded-lg hover:bg-gray-100 transition-colors"
                        @click.stop="navigator.clipboard.writeText('{{ Storage::url($item->file_path) }}'); alert('URL copied!')">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                    </button>
                </div>

                <div class="absolute bottom-0 inset-x-0 p-2 bg-gradient-to-t from-black/60 to-transparent">
                    <span class="text-[10px] text-white truncate block">{{ $item->file_name }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $mediaItems->links() }}
    </div>

    @if ($mediaItems->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
            <svg class="size-16 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-lg font-medium">No media items found</p>
            <p class="text-sm">Start by uploading some images above.</p>
        </div>
    @endif
</div>