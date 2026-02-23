<x-auth-layout title="Phone Verification" description="Verify your phone number to continue">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Verify Phone</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                We've sent a 6-digit code to your phone number
                <span
                    class="font-semibold text-foreground">{{ Str::mask(auth()->user()->phone ?? '', '*', 3, -3) }}</span>
            </p>
        </div>

        <div class="mt-8">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Error Banner -->
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.phone.verify') }}" x-data="{
                submitting: false,
                submitForm() {
                    const inputs = this.$refs.pinInputContainer.querySelectorAll('[data-hs-pin-input-item]');
                    const code = Array.from(inputs).map(input => input.value).join('');
                    this.$refs.hiddenCode.value = code;
                    this.submitting = true;
                }
            }" @submit.prevent="submitForm(); $el.submit()">
                @csrf

                <input type="hidden" name="code" x-ref="hiddenCode">

                <div class="flex justify-center gap-x-3 mb-8" data-hs-pin-input x-ref="pinInputContainer">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text"
                            class="block w-10 h-10 sm:w-12 sm:h-12 text-center text-xl font-bold bg-layer border border-gray-200 rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none"
                            data-hs-pin-input-item {{ $i === 0 ? 'autofocus' : '' }} inputmode="numeric" pattern="[0-9]*"
                            autocomplete="one-time-code">
                    @endfor
                </div>

                <div class="grid gap-y-4">
                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Verify Code</span>
                        <span x-show="submitting" x-cloak>Verifying...</span>
                    </x-button>

                    <p class="text-center text-sm text-muted-foreground">
                        Didn't receive the code?
                        <button type="button" @click="$refs.resendForm.submit()"
                            class="text-primary-500 decoration-2 hover:underline focus:outline-none focus:underline font-medium">
                            Resend Code
                        </button>
                    </p>
                </div>
            </form>

            <form x-ref="resendForm" method="POST" action="{{ route('verification.phone.resend') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</x-auth-layout>