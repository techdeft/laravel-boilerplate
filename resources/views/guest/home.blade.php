<x-guest-layout title="Home" description="Home page description">
    @livewire('hero')

    @livewire('hot-deals')
    @livewire('category')

    <!-- Our Products Section -->
    @livewire('guest.product-section', [
        'title' => 'Our Products',

        'badgeColor' => 'blue',
        'layout' => 'grid',
        'products' => \App\Models\Product::where('is_active', true)->latest()->take(12)->get()
    ], 'products-grid-sec')
    @livewire('brands')
</x-guest-layout>
