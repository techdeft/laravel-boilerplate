<x-auth-layout title="Reset Password" description="Set a new password for your account">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Reset password</h3>
            <p class="mt-2 text-sm text-muted-foreground">Please enter your new password below</p>
        </div>

        <div class="mt-6">
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.update') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf
                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                <div class="grid gap-y-4">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm mb-2 text-foreground">Email address</label>
                        <input type="email" id="email" name="email" value="{{ request('email') }}" autocomplete="email"
                            required
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="email" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm mb-2 text-foreground">New password</label>
                        <input type="password" id="password" name="password" placeholder="New password"
                            autocomplete="new-password" required
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="password" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm mb-2 text-foreground">Confirm
                            password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Confirm password" autocomplete="new-password" required
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="password_confirmation" />
                    </div>

                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Reset password</span>
                        <span x-show="submitting" x-cloak>Resetting...</span>
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-auth-layout>