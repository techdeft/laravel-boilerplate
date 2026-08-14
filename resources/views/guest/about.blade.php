<x-guest-layout>
    @slot('title', 'About Us | MedMall')

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
                    Our Story
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                    Healthcare, reimagined.
                </h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    We are on a mission to make premium pharmacy services, clinical consultations, and health products
                    accessible to everyone, anywhere, at any time.
                </p>
            </div>
        </section>

        <!-- Mission & Details Container -->
        <section class="py-16 lg:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Right Graphics (Moved Left for variety) -->
                <div class="relative order-2 lg:order-1">
                    <div class="grid grid-cols-2 gap-4 relative z-10">
                        <div class="space-y-4 pt-12">
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                alt="Pharmacy"
                                class="w-full h-64 object-cover rounded-3xl shadow-sm border border-gray-100">
                            <img src="https://images.unsplash.com/photo-1550831107-1553da8c8464?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                alt="Pills"
                                class="w-full h-48 object-cover rounded-3xl shadow-sm border border-gray-100">
                        </div>
                        <div class="space-y-4">
                            <img src="https://images.unsplash.com/photo-1631549916768-4119b2e5f926?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                                alt="Doctor"
                                class="w-full h-48 object-cover rounded-3xl shadow-sm border border-gray-100">
                            <div
                                class="w-full h-64 bg-primary-600 rounded-3xl shadow-sm flex items-center justify-center p-8 text-white relative overflow-hidden">
                                <div class="relative z-10 text-center">
                                    <i class="fa-solid fa-heart-pulse text-5xl mb-4"></i>
                                    <h3 class="font-bold text-xl uppercase tracking-wider">Your Health, First.</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Left Text (Moved Right) -->
                <div class="space-y-8 relative order-1 lg:order-2">
                    <div
                        class="inline-flex items-center justify-center p-3 bg-primary-50 rounded-2xl text-primary-600 mb-2">
                        <i class="fa-solid fa-bullseye text-2xl"></i>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight">
                        Bridging the gap between <span class="text-primary-600">patients and care.</span>
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        MedMall was founded on a simple principle: getting the medication and professional health advice
                        you need shouldn't be complicated. By combining modern e-commerce technology with certified
                        medical professionals, we provide a unified platform for all your wellness needs.
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Whether you are ordering your monthly prescriptions, scheduling a quick health consultation,
                        or buying premium wellness products, MedMall guarantees speed, authenticity, and utmost privacy.
                    </p>

                    <div class="pt-8 border-t border-gray-100 flex items-center gap-6 md:gap-12">
                        <div class="text-center md:text-left">
                            <h4 class="text-4xl font-black text-gray-900">10k+</h4>
                            <span
                                class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1 block">Patients</span>
                        </div>
                        <div class="w-px h-12 bg-gray-100 hidden md:block"></div>
                        <div class="text-center md:text-left">
                            <h4 class="text-4xl font-black text-gray-900">50+</h4>
                            <span
                                class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1 block">Pharmacists</span>
                        </div>
                        <div class="w-px h-12 bg-gray-100 hidden md:block"></div>
                        <div class="text-center md:text-left">
                            <h4 class="text-4xl font-black text-gray-900">24/7</h4>
                            <span
                                class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-1 block">Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Values Section -->
        <section class="bg-gray-50 py-20 lg:py-32 border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-primary-600 font-bold tracking-widest uppercase mb-3 text-sm">Why Choose Us</h2>
                    <h3 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight">Our Core Values</h3>
                    <p class="mt-4 text-lg text-gray-600">We hold ourselves to the highest clinical standard, ensuring
                        that every product ordered and consultation booked is handled with absolute care.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Value 1 -->
                    <div
                        class="bg-white rounded-3xl p-10 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                        <div
                            class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 text-2xl shadow-sm mb-6 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Certified Authenticity</h4>
                        <p class="text-gray-600 leading-relaxed italic">"All our medications and products are sourced
                            directly
                            from verified manufacturers. No counterfeits, guaranteed."</p>
                    </div>

                    <!-- Value 2 -->
                    <div
                        class="bg-white rounded-3xl p-10 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                        <div
                            class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 text-2xl shadow-sm mb-6 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Professional Expertise</h4>
                        <p class="text-gray-600 leading-relaxed italic">"Our pharmacist consultation network strictly
                            connects you
                            with fully licensed, vetted professionals."</p>
                    </div>

                    <!-- Value 3 -->
                    <div
                        class="bg-white rounded-3xl p-10 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                        <div
                            class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 text-2xl shadow-sm mb-6 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Rapid Delivery</h4>
                        <p class="text-gray-600 leading-relaxed italic">"We understand that when it comes to your
                            health, time
                            is critical. We prioritize lightning-fast delivery."</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section (Unifying with Contact Page) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="bg-primary-500 rounded-3xl p-12 md:p-20 text-center relative overflow-hidden">
                <!-- Blurred background circles -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary-500 opacity-20 blur-3xl rounded-full"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-primary-400 opacity-10 blur-3xl rounded-full">
                </div>

                <div class="relative z-10">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Ready to take control of your
                        health?
                    </h2>
                    <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto">
                        Whether you need a quick prescription refill or a full medical consultation, we are here to
                        support your wellness journey.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('shop') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-100 transition-all shadow-lg">
                            Browse Pharmacy
                        </a>
                        <a href="{{ route('telehealth') }}"
                            class="w-full sm:w-auto px-8 py-4 border border-white text-white font-bold rounded-xl  transition-all">
                            Book Consultation
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>
</x-guest-layout>