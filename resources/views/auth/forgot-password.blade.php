<x-auth-layout title="Forgot Password" description="Reset your password">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Forgot password?</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                Enter your email to receive a password reset link
            </p>
        </div>

        <div class="mt-6">
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf
                <div class="grid gap-y-4">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm mb-2 text-foreground">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="email@example.com" autocomplete="email" required autofocus
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="email" />
                    </div>

                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Email password reset link</span>
                        <span x-show="submitting" x-cloak>Sending...</span>
                    </x-button>
                </div>
            </form>

            <p class="mt-4 text-center text-sm text-muted-foreground">
                Or, return to
                <a href="{{ route('login') }}" class="text-primary-500 hover:underline font-medium">log in</a>
            </p>
        </div>
    </div>
</x-auth-layout>