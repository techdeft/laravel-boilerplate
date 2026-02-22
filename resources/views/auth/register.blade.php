<x-auth-layout title="Register" description="Create a new account">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Create an account</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                Already have an account?
                <a class="text-primary-500 decoration-2 hover:underline focus:outline-none font-medium"
                    href="{{ route('login') }}" wire:navigate>Log in</a>
            </p>
        </div>

        <div class="mt-6">
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('register.store') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf
                <div class="grid gap-y-4">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm mb-2 text-foreground">Full name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe"
                            autocomplete="name" required autofocus
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="name" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm mb-2 text-foreground">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="email@example.com" autocomplete="email" required
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="email" />
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm mb-2 text-foreground">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                placeholder="Password" autocomplete="new-password" required
                                class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">

                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 hover:text-primary-500 focus:outline-none">
                                <svg x-show="!showPassword" class="shrink-0 size-4.5" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg x-cloak x-show="showPassword" class="shrink-0 size-4.5"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                    </path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61">
                                    </path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                            </button>
                        </div>
                        <x-action-message field="password" />
                    </div>

                    <!-- Confirm Password -->
                    <div x-data="{ showConfirmPassword: false }">
                        <label for="password_confirmation" class="block text-sm mb-2 text-foreground">Confirm
                            password</label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm password" autocomplete="new-password"
                                required
                                class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">

                            <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer text-gray-400 hover:text-primary-500 focus:outline-none">
                                <svg x-show="!showConfirmPassword" class="shrink-0 size-4.5"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg x-cloak x-show="showConfirmPassword" class="shrink-0 size-4.5"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                    </path>
                                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61">
                                    </path>
                                    <line x1="2" x2="22" y1="2" y2="22"></line>
                                </svg>
                            </button>
                        </div>
                        <x-action-message field="password_confirmation" />
                    </div>

                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Create account</span>
                        <span x-show="submitting" x-cloak>Creating...</span>
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-auth-layout>