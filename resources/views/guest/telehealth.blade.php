<x-guest-layout>
    @slot('title', 'Consultation | Book a Pharmacist')

    <div class="min-h-screen bg-slate-50 font-sans selection:bg-pink-100 selection:text-pink-600">
        {{-- Exact Match of zzz.html Hero Section --}}
        <section class="bg-gray-50 relative mx-auto max-w-[1440px] py-14 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 xl:px-0">
                <div class="flex flex-col gap-11 lg:flex-row lg:items-center lg:gap-20">
                    <div class="lg:w-1/2">
                        <div>
                            <h1 class="text-gray-900 sm:text-5xl mb-5 text-4xl font-semibold lg:pr-10 tracking-tight">
                                Consultation.
                            </h1>
                            <p class="text-gray-500 text-base lg:pr-10">
                                Get expert clinical advice and personalized medication management from professional
                                pharmacists. Trusted by thousands.
                            </p>
                            <div class="mt-8 flex w-full flex-col gap-4 sm:mt-10 sm:flex-row">
                                <a href="{{ route('booking.create') }}"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-primary-500 text-white rounded-lg font-medium hover:bg-primary-600 transition-colors">
                                    Book Instant Session
                                </a>
                                <a href="#how-it-works"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-900 border border-gray-200 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                    How it works
                                </a>
                            </div>
                            <div class="mt-12 flex items-center gap-3.5">
                                <div class="flex -space-x-3">
                                    <img src="https://cdn-tailgrids.b-cdn.net/3.0/marketing/hero/hero-04/avatar-1.png"
                                        class="ring-gray-50 h-10 w-10 rounded-full ring-2" alt="avatar" />
                                    <img src="https://cdn-tailgrids.b-cdn.net/3.0/marketing/hero/hero-04/avatar-2.png"
                                        class="ring-gray-50 h-10 w-10 rounded-full ring-2" alt="avatar" />
                                    <img src="https://cdn-tailgrids.b-cdn.net/3.0/marketing/hero/hero-04/avatar-3.png"
                                        class="ring-gray-50 h-10 w-10 rounded-full ring-2" alt="avatar" />
                                </div>
                                <div>
                                    <div class="flex items-center gap-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="size-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-gray-500 text-xs mt-1">
                                        Trusted by 20k+ Users
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/2">

                        <img src="{{ asset('images/phamacy-consult.jpg') }}" class="mx-auto rounded-2xl"
                            alt="Telehealth Consultation App Dashboard" />

                    </div>
                </div>
            </div>
        </section>

        {{-- Features Grid --}}
        <section id="how-it-works" class="py-32 container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <span class="text-primary-600 font-bold text-sm tracking-wider uppercase">How it works</span>
                <h2 class="text-4xl lg:text-5xl font-semibold text-gray-900 tracking-tight">The Modern Way to <br>
                    Consult Your Pharmacist
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div
                    class="bg-white p-10 rounded-2xl border border-gray-100 hover:shadow-lg hover:border-gray-200 transition-all text-center space-y-5">
                    <div class="mx-auto rounded-xl flex items-center justify-center text-primary-500 mb-6">
                        <i class="far fa-comments text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Immediate Assistance</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">No wait times. Start a video session with a
                        pharmacist right away for instant medical clarity.</p>
                </div>

                {{-- Feature 2 --}}
                <div
                    class="bg-white p-10 rounded-2xl border border-gray-100 hover:shadow-lg hover:border-gray-200 transition-all text-center space-y-5">
                    <div class="mx-auto rounded-xl flex items-center justify-center text-primary-500 mb-6">
                        <i class="far fa-calendar-check text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Expert Knowledge</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Licensed professionals trained in various
                        health topics, medications, and lifestyle wellness.</p>
                </div>

                {{-- Feature 3 --}}
                <div
                    class="bg-white p-10 rounded-2xl border border-gray-100 hover:shadow-lg hover:border-gray-200 transition-all text-center space-y-5">
                    <div class="mx-auto rounded-xl flex items-center justify-center text-primary-500 mb-6">
                        <i class="far fa-user-circle text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Personalized Care</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Tailored medical advice specifically for your
                        unique situation, prescriptions, or symptoms.</p>
                </div>
            </div>
        </section>

        {{-- Final CTA Section (Product Details Style) --}}
        <section class="py-24 lg:py-32 bg-gray-50 px-6">
            <div class="container mx-auto max-w-5xl">
                <div
                    class="bg-gradient-to-br from-[#2b1770] to-[#3f238f] rounded-3xl shadow-2xl p-10 lg:p-16 text-white space-y-8 relative overflow-hidden group flex flex-col items-center text-center">
                    <div
                        class="absolute -right-10 -top-10 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <i class="fa-solid fa-prescription-bottle-medical text-[200px]"></i>
                    </div>
                    <div
                        class="absolute -left-10 -bottom-10 opacity-5 group-hover:scale-110 transition-transform duration-700">
                        <i class="fa-solid fa-stethoscope text-[200px]"></i>
                    </div>

                    <div class="relative z-10 max-w-3xl space-y-6">
                        <h2 class="text-3xl lg:text-5xl font-bold leading-tight">Need to Talk to a Pharmacist?</h2>
                        <p class="text-base lg:text-lg text-purple-100 leading-relaxed font-medium">
                            Our licensed professional pharmacists are available to provide expert advice on your
                            medications and health concerns.
                        </p>

                        <div class="flex justify-center pt-4">
                            <a href="{{ route('booking.create') }}"
                                class="inline-flex items-center justify-center px-10 py-5 bg-white text-[#2b1770] font-bold rounded-xl text-sm hover:bg-purple-50 transition-all shadow-xl uppercase tracking-wider">
                                <i class="fa-solid fa-comments-medical mr-2 text-lg"></i>
                                Consult a Pharmacist
                            </a>
                        </div>
                    </div>


                </div>
            </div>
        </section>
    </div>

    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        .container {
            max-width: 1280px;
        }
    </style>
</x-guest-layout>