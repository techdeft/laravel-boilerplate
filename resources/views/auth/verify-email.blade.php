<x-auth-layout title="Verify Email" description="Verify your email address">
    <div class="p-4 sm:p-7">
        <div class="text-center">
            <h3 class="block text-2xl font-bold text-foreground">Verify your email</h3>
            <p class="mt-2 text-sm text-muted-foreground">
                Please verify your email address by clicking on the link we just emailed to you.
            </p>
        </div>

        <div class="mt-6">
            @if (session('status') == 'verification-link-sent')
                <p class="mb-4 text-sm text-center text-green-600 font-medium">
                    A new verification link has been sent to your email address.
                </p>
            @endif

            <div class="flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}" x-data="{ submitting: false }"
                    @submit="submitting = true">
                    @csrf
                    <x-button :full="true" x-bind:disabled="submitting">
                        <span x-show="!submitting">Resend verification email</span>
                        <span x-show="submitting" x-cloak>Sending...</span>
                    </x-button>
                </form>

                <form method="POST" action="{{ route('logout') }}" x-data="{ submitting: false }"
                    @submit="submitting = true">
                    @csrf
                    <x-button :full="true" variant="ghost" x-bind:disabled="submitting">
                        <span x-show="!submitting">Log out</span>
                        <span x-show="submitting" x-cloak>Logging out...</span>
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-auth-layout>