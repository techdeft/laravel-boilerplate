<x-guest-layout>
    @slot('title', 'Contact Us | Medmall')

    <div class="min-h-screen bg-white font-sans selection:bg-gray-100 selection:text-gray-900 overflow-x-hidden">

        <!-- Grid Background Pattern & Header -->
        <section class="relative pt-20 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
            <!-- Grid Background Overlay -->
            <div class="absolute inset-x-0 top-0 h-[800px] pointer-events-none -z-10" style="background-image: 
                    linear-gradient(to right, #f1f5f9 1px, transparent 1px),
                    linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
                    background-size: 40px 40px;
                    mask-image: radial-gradient(ellipse at center, black, transparent 80%);">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-primary-50 text-primary-700 text-sm font-semibold mb-6">
                    Contact us
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                    Contact our friendly team
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    Let us know how we can help. We're here to answer any questions you may have.
                </p>
            </div>
        </section>

        <!-- Information Cards Grid -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Chat to sales -->
                <div
                    class="group p-6 rounded-2xl bg-white border border-gray-200 hover:border-primary-300 hover:shadow-lg transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-regular fa-comment-dots text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Chat to sales</h3>
                    <p class="text-gray-600 text-sm mb-4">Speak to our friendly team.</p>
                    <a href="mailto:sales@Medmall.ng" class="text-primary-600 font-semibold text-sm hover:underline">
                        sales@Medmall.ng
                    </a>
                </div>

                <!-- Chat to support -->
                <div
                    class="group p-6 rounded-2xl bg-white border border-gray-200 hover:border-primary-300 hover:shadow-lg transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-regular fa-life-ring text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Chat to support</h3>
                    <p class="text-gray-600 text-sm mb-4">We're here to help.</p>
                    <a href="mailto:support@Medmall.ng" class="text-primary-600 font-semibold text-sm hover:underline">
                        support@Medmall.ng
                    </a>
                </div>

                <!-- Visit us -->
                <div
                    class="group p-6 rounded-2xl bg-white border border-gray-200 hover:border-primary-300 hover:shadow-lg transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-regular fa-map text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Visit us</h3>
                    <p class="text-gray-600 text-sm mb-4">Visit our office HQ.</p>
                    <a href="https://www.google.com/maps/dir//Medmall+Pharmacy+ltd,+Former+bank+of+agric+building,+36,+N.U.D+road,+Oke+Yeke,+Isabo,+Abeokuta/data=!4m6!4m5!1m1!4e2!1m2!1m1!1s0x103a4bf30ea7f463:0x74243635a203cf47?sa=X&ved=1t:57443&ictx=111"
                        class="text-primary-600 font-semibold text-sm hover:underline">
                        View on Google Maps
                    </a>
                </div>

                <!-- Call us -->
                <div
                    class="group p-6 rounded-2xl bg-white border border-gray-200 hover:border-primary-300 hover:shadow-lg transition-all duration-300">
                    <div
                        class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-regular fa-phone text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Call us</h3>
                    <p class="text-gray-600 text-sm mb-4">Mon-Fri from 8am to 5pm.</p>
                    <a href="tel:+2348000000000" class="text-primary-600 font-semibold text-sm hover:underline">
                        +234 800 000 0000
                    </a>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Frequently asked questions</h2>
            </div>

            <div class="space-y-4" x-data="{ activeAccordion: 1 }">
                <!-- FAQ Item 1 -->
                <div class="border-b border-gray-200 py-6">
                    <button @click="activeAccordion = activeAccordion === 1 ? null : 1"
                        class="flex w-full items-center justify-between text-left group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-video text-sm"></i>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">How does the consultation
                                work?</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="activeAccordion === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeAccordion === 1" x-collapse x-cloak>
                        <div class="pt-4 pl-14 pr-4">
                            <p class="text-gray-600 leading-relaxed">
                                Our Consultation service connects you with licensed pharmacists for expert advice.
                                Simply
                                book a slot through our booking page, and you'll receive a link for a secure video or
                                audio call at your scheduled time.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border-b border-gray-200 py-6">
                    <button @click="activeAccordion = activeAccordion === 2 ? null : 2"
                        class="flex w-full items-center justify-between text-left group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-shield-check text-sm"></i>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">Are your medications authentic?</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="activeAccordion === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeAccordion === 2" x-collapse x-cloak>
                        <div class="pt-4 pl-14 pr-4">
                            <p class="text-gray-600 leading-relaxed">
                                Yes, absolute authenticity is our guarantee. We source all our medications and health
                                products directly from verified manufacturers and certified distributors to ensure you
                                receive only the best.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border-b border-gray-200 py-6">
                    <button @click="activeAccordion = activeAccordion === 3 ? null : 3"
                        class="flex w-full items-center justify-between text-left group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-truck-fast text-sm"></i>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">How fast is delivery?</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="activeAccordion === 3 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeAccordion === 3" x-collapse x-cloak>
                        <div class="pt-4 pl-14 pr-4">
                            <p class="text-gray-600 leading-relaxed">
                                We offer rapid delivery across major cities. Most orders are delivered within 24 hours,
                                while emergency medications are prioritized for even faster arrival at your doorstep.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border-b border-gray-200 py-6">
                    <button @click="activeAccordion = activeAccordion === 4 ? null : 4"
                        class="flex w-full items-center justify-between text-left group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-calendar-xmark text-sm"></i>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">Can I cancel a booking?</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-300"
                            :class="activeAccordion === 4 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeAccordion === 4" x-collapse x-cloak>
                        <div class="pt-4 pl-14 pr-4">
                            <p class="text-gray-600 leading-relaxed">
                                Yes, you can cancel or reschedule your consultation booking up to 2 hours before the
                                scheduled time through your customer dashboard.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <button
                    class="px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-all shadow-md">
                    Load more
                </button>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="bg-gray-900 rounded-3xl p-12 md:p-20 text-center relative overflow-hidden">
                <!-- Blurred background circles -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary-500 opacity-20 blur-3xl rounded-full"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-primary-400 opacity-10 blur-3xl rounded-full">
                </div>

                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Ready to level up your healthcare?
                    </h2>
                    <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto">
                        Join over 10,000 satisfied patients who trust Medmall for their pharmacy and wellness needs.
                        Start your journey to better health today.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('shop') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-all shadow-lg">
                            Get started
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>
</x-guest-layout>