<?php

use Livewire\Volt\Component;
use App\Models\HomeSlider;

new class extends Component {
    public function with()
    {
        $sliders = HomeSlider::where('is_active', true)->orderBy('order')->get();

        // Fallback if no sliders are configured
        if ($sliders->isEmpty()) {
            $sliders = collect([
                (object) [
                    'id' => 1,
                    'image_path' => 'assets/hero/pharmacist.png',
                    'title' => 'Need to talk to a Pharmacist?',
                    'subtitle' => 'Schedule a session with our qualified and competent pharmacist today!',
                    'link_url' => '#'
                ],
                (object) [
                    'id' => 2,
                    'image_path' => 'assets/hero/pharmacy.png',
                    'title' => 'Your Health, Our Priority.',
                    'subtitle' => 'Get genuine medicines and healthcare products delivered to your doorstep.',
                    'link_url' => '#'
                ]
            ]);
        }

        return [
            'slides' => $sliders
        ];
    }
};
?>

<div x-data="{ 
    activeSlide: 0, 
    slides: @js($slides),
    next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
    init() { if(this.slides.length > 1) setInterval(() => this.next(), 7000) }
}" class="relative w-full h-[400px] md:h-[550px] overflow-hidden bg-gray-100 group">

    <!-- Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-1000"
            x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="absolute inset-0">

            <!-- Background Image -->
            <img :src="slide.image_path.startsWith('http') || slide.image_path.startsWith('assets') ? slide.image_path : '/storage/' + slide.image_path"
                :alt="slide.title" class="absolute inset-0 w-full h-full object-cover">

            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/40 to-transparent"></div>

            <!-- Content -->
            <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                <div class="max-w-xl space-y-6">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-[#e11d48] leading-tight" x-text="slide.title">
                    </h1>
                    <p class="text-xl md:text-2xl text-blue-900/80 font-medium leading-relaxed" x-text="slide.subtitle">
                    </p>

                    <div class="pt-4" x-show="slide.link_url">
                        <a :href="slide.link_url"
                            class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-300 bg-blue-900 rounded-xl hover:bg-blue-800 hover:shadow-xl hover:-translate-y-1 focus:outline-none shadow-lg">
                            <span>Get Started</span>
                            <svg class="size-5 ms-2 transition-transform group-hover:translate-x-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Navigation Dots -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-x-3 z-20">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="activeSlide = index"
                :class="activeSlide === index ? 'w-10 bg-blue-900' : 'w-3 bg-blue-900/30'"
                class="h-1.5 rounded-full transition-all duration-500 hover:bg-blue-900/50">
            </button>
        </template>
    </div>

    <!-- Arrows (Desktop Only) -->
    <template x-if="slides.length > 1">
        <div class="contents">
            <button @click="prev()"
                class="absolute left-6 top-1/2 -translate-y-1/2 size-12 hidden md:flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-blue-900 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white/40 z-30">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button @click="next()"
                class="absolute right-6 top-1/2 -translate-y-1/2 size-12 hidden md:flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-blue-900 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white/40 z-30">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </template>
</div>