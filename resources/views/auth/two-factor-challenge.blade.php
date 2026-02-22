<x-auth-layout title="Two-Factor Confirmation" description="Confirm access with your authenticator">
    <div class="p-4 sm:p-7" x-data="{ recovery: false }">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Two-factor Confirmation</h3>
            <p class="mt-2 text-sm text-muted-foreground" x-show="!recovery">
                Please confirm access to your account by entering the authentication code provided by your authenticator
                application.
            </p>
            <p class="mt-2 text-sm text-muted-foreground" x-show="recovery" x-cloak>
                Please confirm access to your account by entering one of your emergency recovery codes.
            </p>
        </div>

        <div class="mt-6">
            <x-auth-session-status class="text-center mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('two-factor.login') }}" x-data="{ submitting: false }"
                @submit="submitting = true">
                @csrf
                <div class="grid gap-y-4">

                    <!-- OTP Code -->
                    <div x-show="!recovery">
                        <label for="code" class="block text-sm mb-2 text-foreground">Authentication Code</label>
                        <input type="text" inputmode="numeric" id="code" name="code" placeholder="6-digit code"
                            autofocus autocomplete="one-time-code" x-bind:required="!recovery"
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="code" />
                    </div>

                    <!-- Recovery Code -->
                    <div x-show="recovery" x-cloak>
                        <label for="recovery_code" class="block text-sm mb-2 text-foreground">Recovery Code</label>
                        <input type="text" id="recovery_code" name="recovery_code" placeholder="Recovery code"
                            autocomplete="off" x-bind:required="recovery"
                            class="py-2.5 px-4 block w-full border border-gray-200 rounded-lg text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 disabled:opacity-50">
                        <x-action-message field="recovery_code" />
                    </div>

                    <div class="flex items-center justify-end mt-2">
                        <button type="button"
                            class="text-sm text-primary-500 hover:underline focus:outline-none focus:underline font-medium cursor-pointer"
                            @click="recovery = !recovery; $nextTick(() => { if (recovery) { $refs.recovery_code.focus() } else { $refs.code.focus() } })">
                            <span x-show="!recovery">Use a recovery code</span>
                            <span x-show="recovery" x-cloak>Use an authentication code</span>
                        </button>
                    </div>

                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Log in</span>
                        <span x-show="submitting" x-cloak>Logging in...</span>
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-auth-layout>