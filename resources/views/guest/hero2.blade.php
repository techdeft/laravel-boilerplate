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

<div>
    <!-- Slider -->
    <div class="px-4 sm:px-6 lg:px-8 py-10">
        <div data-hs-carousel='{
      "loadingClasses": "opacity-0"
    }' class="relative">
            <div
                class="hs-carousel relative overflow-hidden w-full max-h-100 md:h-[calc(100vh-106px)]  bg-surface rounded-2xl">
                <div
                    class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
                    <!-- Item 1 -->
                    <div class="hs-carousel-slide">
                        <div
                            class="h-[500px] md:h-[calc(100vh-106px)] flex flex-col bg-[url('https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center bg-no-repeat relative">
                            <div class="absolute inset-0 bg-linear-to-r from-blue-900/40 to-transparent"></div>
                            <div class="mt-auto relative z-10 w-full md:max-w-2xl ps-6 pb-8 md:ps-16 md:pb-20">
                                <span
                                    class="inline-block px-3 py-1 mb-4 text-[10px] font-black uppercase tracking-[0.2em] bg-blue-900 text-white rounded-full">Authenticity
                                    Guaranteed</span>
                                <h2
                                    class="text-4xl md:text-6xl font-black text-white leading-[1.1] mb-6 drop-shadow-sm">
                                    Your Health. <br>Our Priority.
                                </h2>
                                <p class="text-lg md:text-xl text-white/90 font-medium mb-8 max-w-lg leading-relaxed">
                                    Get genuine medicines and healthcare essentials delivered with care, directly to
                                    your doorstep.
                                </p>
                                <div class="flex items-center gap-4">
                                    <a class="py-4 px-8 inline-flex items-center gap-x-2 text-sm font-black rounded-xl bg-white text-blue-900 hover:bg-gray-100 transition-all shadow-xl shadow-blue-900/20"
                                        href="{{ route('shop') }}" wire:navigate>
                                        Shop All Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="hs-carousel-slide">
                        <div
                            class="h-[500px] md:h-[calc(100vh-106px)] flex flex-col bg-[url('https://images.unsplash.com/photo-1559839734-2b71f1536783?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center bg-no-repeat relative">
                            <div class="absolute inset-0 bg-linear-to-r from-emerald-900/40 to-transparent"></div>
                            <div class="mt-auto relative z-10 w-full md:max-w-2xl ps-6 pb-8 md:ps-16 md:pb-20">
                                <span
                                    class="inline-block px-3 py-1 mb-4 text-[10px] font-black uppercase tracking-[0.2em] bg-emerald-600 text-white rounded-full">Expert
                                    Consultation</span>
                                <h2
                                    class="text-4xl md:text-6xl font-black text-white leading-[1.1] mb-6 drop-shadow-sm">
                                    Talk to a <br>Pharmacist.
                                </h2>
                                <p class="text-lg md:text-xl text-white/90 font-medium mb-8 max-w-lg leading-relaxed">
                                    Professional advice is just a click away. Book a session with our qualified experts
                                    today.
                                </p>
                                <div class="flex items-center gap-4">
                                    <a class="py-4 px-8 inline-flex items-center gap-x-2 text-sm font-black rounded-xl bg-white text-emerald-600 hover:bg-gray-100 transition-all shadow-xl shadow-emerald-950/20"
                                        href="#">
                                        Schedule Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="hs-carousel-slide">
                        <div
                            class="h-[500px] md:h-[calc(100vh-106px)] flex flex-col bg-[url('https://images.unsplash.com/photo-1512678080530-7760d81faba6?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center bg-no-repeat relative">
                            <div class="absolute inset-0 bg-linear-to-r from-pink-900/40 to-transparent"></div>
                            <div class="mt-auto relative z-10 w-full md:max-w-2xl ps-6 pb-8 md:ps-16 md:pb-20">
                                <span
                                    class="inline-block px-3 py-1 mb-4 text-[10px] font-black uppercase tracking-[0.2em] bg-pink-500 text-white rounded-full">Beauty
                                    & Wellness</span>
                                <h2
                                    class="text-4xl md:text-6xl font-black text-white leading-[1.1] mb-6 drop-shadow-sm">
                                    Premium Care <br>for Everyone.
                                </h2>
                                <p class="text-lg md:text-xl text-white/90 font-medium mb-8 max-w-lg leading-relaxed">
                                    From skincare to essential wellness products, explore our curated selection for your
                                    best self.
                                </p>
                                <div class="flex items-center gap-4">
                                    <a class="py-4 px-8 inline-flex items-center gap-x-2 text-sm font-black rounded-xl bg-white text-pink-500 hover:bg-gray-100 transition-all shadow-xl shadow-pink-950/20"
                                        href="{{ route('shop', ['category' => 'personal-care']) }}" wire:navigate>
                                        Explore Beauty
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Item -->
                </div>
            </div>

            <!-- Arrows -->
            <button type="button"
                class="hs-carousel-prev hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-12 h-full text-inverse hover:bg-plain/20 rounded-s-2xl focus:outline-hidden focus:bg-plain/20">
                <span class="text-2xl" aria-hidden="true">
                    <svg class="shrink-0 size-3.5 md:size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z">
                        </path>
                    </svg>
                </span>
                <span class="sr-only">Previous</span>
            </button>

            <button type="button"
                class="hs-carousel-next hs-carousel-disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-12 h-full text-inverse hover:bg-plain/20 rounded-e-2xl focus:outline-hidden focus:bg-plain/20">
                <span class="sr-only">Next</span>
                <span class="text-2xl" aria-hidden="true">
                    <svg class="shrink-0 size-3.5 md:size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z">
                        </path>
                    </svg>
                </span>
            </button>
            <!-- End Arrows -->
        </div>
    </div>
    <!-- End Slider -->
</div>