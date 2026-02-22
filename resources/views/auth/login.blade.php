<x-auth-layout title="Login" description="Login page description">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Sign in</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                Don't have an account yet?
                <a class="text-primary-500 decoration-2 hover:underline focus:outline-none focus:underline font-medium"
                    href="{{ route('register') }}" wire:navigate>
                    Sign up here
                </a>
            </p>
        </div>

        <div class="mt-5">
            <!-- Google Sign-in -->
            <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-gray-100 border border-gray-200 text-foreground shadow-sm hover:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none focus:outline-none"
                href="#">
                <svg class="w-4 h-auto" width="46" height="47" viewBox="0 0 46 47" fill="none">
                    <path
                        d="M46 24.0287C46 22.09 45.8533 20.68 45.5013 19.2112H23.4694V27.9356H36.4069C36.1429 30.1094 34.7347 33.37 31.5957 35.5731L31.5663 35.8669L38.5191 41.2719L38.9885 41.3306C43.4477 37.2181 46 31.1669 46 24.0287Z"
                        fill="#4285F4" />
                    <path
                        d="M23.4694 47C29.8061 47 35.1161 44.9144 39.0179 41.3012L31.625 35.5437C29.6301 36.9244 26.9898 37.8937 23.4987 37.8937C17.2793 37.8937 12.0281 33.7812 10.1505 28.1412L9.88649 28.1706L2.61097 33.7812L2.52296 34.0456C6.36608 41.7125 14.287 47 23.4694 47Z"
                        fill="#34A853" />
                    <path
                        d="M10.1212 28.1413C9.62245 26.6725 9.32908 25.1156 9.32908 23.5C9.32908 21.8844 9.62245 20.3275 10.0918 18.8588V18.5356L2.75765 12.8369L2.52296 12.9544C0.909439 16.1269 0 19.7106 0 23.5C0 27.2894 0.909439 30.8731 2.49362 34.0456L10.1212 28.1413Z"
                        fill="#FBBC05" />
                    <path
                        d="M23.4694 9.07688C27.8699 9.07688 30.8622 10.9863 32.5344 12.5725L39.1645 6.11C35.0867 2.32063 29.8061 0 23.4694 0C14.287 0 6.36607 5.2875 2.49362 12.9544L10.0918 18.8588C11.9987 13.1894 17.25 9.07688 23.4694 9.07688Z"
                        fill="#EB4335" />
                </svg>
                Sign in with Google
            </a>

            <!-- Divider -->
            <div class="py-3 flex items-center text-xs text-muted-foreground uppercase
                before:flex-1 before:border-t before:border-gray-200 before:me-6
                after:flex-1 after:border-t after:border-gray-200 after:ms-6">
                Or
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Auth Error Banner -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.store') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf

                <div class="grid gap-y-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm mb-2 text-foreground">Email address</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" placeholder="Enter your email"
                                value="{{ old('email') }}"
                                class="py-2.5 sm:py-3 px-4 block w-full border rounded-lg sm:text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none "
                                required aria-describedby="email-error">
                        </div>

                    </div>
                    <!-- End Email -->

                    <!-- Password -->
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm mb-2 text-foreground">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                                placeholder="Enter your password"
                                class="py-2.5 sm:py-3 px-4 block w-full border rounded-lg sm:text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-400' : 'border-gray-200' }}"
                                required aria-describedby="password-error">

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
                        @error('password')
                            <p class="text-xs text-red-600 mt-2" id="password-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- End Password -->

                    <!-- Remember me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="checkbox" name="checkbox" type="checkbox"
                                class="shrink-0 size-4 bg-transparent border border-gray-200 rounded-sm text-primary-500 focus:ring-1 focus:ring-primary-500 focus:ring-offset-0 checked:bg-primary-500 checked:border-primary-500 disabled:opacity-50 disabled:pointer-events-none">
                            <label for="checkbox" class="ms-3 text-sm text-foreground">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-sm text-primary-500 decoration-2 hover:underline focus:outline-none focus:underline font-medium">
                            Forgot password?
                        </a>
                    </div>
                    <!-- End Remember me -->


                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">
                            Sign in
                        </span>

                        <span x-show="submitting" x-cloak>
                            Signing in ...
                        </span>

                    </x-button>



                </div>
            </form>
            <!-- End Form -->
        </div>
    </div>
</x-auth-layout>