<x-auth-layout title="Confirm Password" description="Confirm your password to continue">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Confirm password</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                This is a secure area. Please confirm your password before continuing.
            </p>
        </div>

        <div class="mt-6">
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.confirm.store') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf
                <div class="grid gap-y-4">

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm mb-2 text-foreground">Password</label>
                        <input type="password" id="password" name="password" placeholder="Your password"
                            autocomplete="current-password" required
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="password" />
                    </div>

                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Confirm</span>
                        <span x-show="submitting" x-cloak>Confirming...</span>
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-auth-layout>