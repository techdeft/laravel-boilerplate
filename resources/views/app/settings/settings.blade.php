<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

new class extends Component {

    // ── Profile fields ────────────────────────────────────────────
    public string $name = '';
    public string $email = '';

    // ── Password fields ───────────────────────────────────────────
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    // ── Active tab ────────────────────────────────────────────────
    public string $tab = 'profile';

    // ── TWO FACTOR AUTHENTICATION ─────────────────────────────────
    public bool $showingQrCode = false;
    public bool $showingConfirmation = false;
    public bool $showingRecoveryCodes = false;
    public string $twoFactorCode = '';
    public string $qrCodeSvg = '';
    public string $setupKey = '';

    public function mount(): void
    {
        if (Fortify::confirmsTwoFactorAuthentication() && is_null(Auth::user()->two_factor_confirmed_at)) {
            app(DisableTwoFactorAuthentication::class)(Auth::user());
        }

        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    // ── PROFILE ───────────────────────────────────────────────────

    #[Layout('layouts.app.app')]
    public function updateProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated');
        Session::flash('profile_success', 'Profile updated successfully.');
    }

    // ── PASSWORD ──────────────────────────────────────────────────

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        Session::flash('password_success', 'Password changed successfully.');
    }

    // ── DELETE ACCOUNT ────────────────────────────────────────────

    public function deleteAccount(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect(route('home'), navigate: true);
    }

    // ── TWO FACTOR AUTH ───────────────────────────────────────────

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        $this->resetErrorBag();

        $enable(Auth::user());

        $this->showingQrCode = true;

        if (Fortify::confirmsTwoFactorAuthentication()) {
            $this->showingConfirmation = true;
            $this->showingRecoveryCodes = false;
        } else {
            $this->showingRecoveryCodes = true;
        }

        $this->loadSetupData();
    }

    private function loadSetupData(): void
    {
        $user = Auth::user()->fresh();

        try {
            $this->qrCodeSvg = $user?->twoFactorQrCodeSvg();
            $this->setupKey = decrypt($user->two_factor_secret);
        } catch (\Exception $e) {
            $this->addError('setupData', 'Failed to fetch setup data.');
            $this->reset('qrCodeSvg', 'setupKey');
        }
    }

    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $confirm): void
    {
        $this->resetErrorBag();

        try {
            $confirm(Auth::user(), $this->twoFactorCode);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('twoFactorCode', __('The provided two factor authentication code was invalid.'));
            return;
        }

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = true;
    }

    public function showRecoveryCodes(): void
    {
        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $generate(Auth::user());

        $this->showingRecoveryCodes = true;
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        $disable(Auth::user());

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;
        $this->qrCodeSvg = '';
        $this->setupKey = '';
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    // ── COMPUTED ──────────────────────────────────────────────────

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && !Auth::user()->hasVerifiedEmail();
    }
}; ?>


<div class="max-w-7xl mx-auto space-y-6">

    {{-- Page heading --}}
    <div>
        <h2 class="text-xl font-semibold text-foreground">Settings</h2>
        <p class="text-sm text-muted-foreground mt-1">Manage your profile, password and account preferences.</p>
    </div>

    {{-- Tab Bar --}}
    <div class="flex gap-1 border-b border-gray-200">
        @foreach (['profile' => 'Profile', 'password' => 'Password', 'two-factor' => 'Two-Factor Auth', 'danger' => 'Danger Zone'] as $key => $label)
            <button wire:click="$set('tab', '{{ $key }}')" @class([
                'px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-colors',
                'border-primary-500 text-primary-600' => $tab === $key,
                'border-transparent text-muted-foreground hover:text-foreground' => $tab !== $key,
            ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- ══ TAB: Profile ══════════════════════════════════════════ --}}
    @if ($tab === 'profile')
        <div class="bg-card border border-gray-200 rounded-xl p-6 space-y-5">
            <div>
                <h3 class="text-base font-semibold text-foreground">Profile Information</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Update your name and email address.</p>
            </div>

            @if (session('profile_success'))
                <div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('profile_success') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-4">
                {{-- Avatar placeholder --}}
                <div class="flex items-center gap-4">
                    <div
                        class="flex items-center justify-center size-16 rounded-full bg-primary-500 text-white text-xl font-bold select-none">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-muted-foreground">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-foreground mb-1.5">Full name</label>
                        <input wire:model="name" type="text" id="name" autocomplete="name"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent
                                                                   placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500
                                                                   @error('name') border-red-400 focus:ring-red-400 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-foreground mb-1.5">Email
                            address</label>
                        <input wire:model="email" type="email" id="email" autocomplete="email"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent
                                                                   placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500
                                                                   @error('email') border-red-400 focus:ring-red-400 @enderror">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if ($this->hasUnverifiedEmail)
                    <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-sm text-amber-700">
                        Your email address is unverified.
                        <button type="button" wire:click="resendVerificationNotification"
                            class="underline font-medium ml-1">Re-send verification email</button>
                    </div>
                @endif

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-primary-500 text-white text-sm font-medium hover:bg-primary-600 transition-colors">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ══ TAB: Password ═════════════════════════════════════════ --}}
    @if ($tab === 'password')
        <div class="bg-card border border-gray-200 rounded-xl p-6 space-y-5">
            <div>
                <h3 class="text-base font-semibold text-foreground">Change Password</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Use a strong password you don't use elsewhere.</p>
            </div>

            @if (session('password_success'))
                <div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('password_success') }}
                </div>
            @endif

            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-foreground mb-1.5">Current
                        password</label>
                    <input wire:model="current_password" type="password" id="current_password"
                        autocomplete="current-password"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent
                                                               placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500
                                                               @error('current_password') border-red-400 focus:ring-red-400 @enderror">
                    @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-foreground mb-1.5">New password</label>
                    <input wire:model="password" type="password" id="password" autocomplete="new-password"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent
                                                               placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500
                                                               @error('password') border-red-400 focus:ring-red-400 @enderror">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-foreground mb-1.5">Confirm
                        new password</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation"
                        autocomplete="new-password"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent
                                                               placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-primary-500 text-white text-sm font-medium hover:bg-primary-600 transition-colors">
                        Update password
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ══ TAB: Two-Factor Auth ═══════════════════════════════════ --}}
    @if ($tab === 'two-factor')
        <div class="bg-card border border-gray-200 rounded-xl p-6 space-y-5">
            <div>
                <h3 class="text-base font-semibold text-foreground">Two-Factor Authentication</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Add additional security to your account using two-factor
                    authentication.</p>
            </div>

            @if (!$this->user->hasEnabledTwoFactorAuthentication() && !$showingQrCode)
                <div class="space-y-4">
                    <p class="text-sm text-foreground">
                        When two-factor authentication is enabled, you will be prompted for a secure, random token during
                        authentication. You may retrieve this token from your phone's Google Authenticator application.
                    </p>
                    <button wire:click="enableTwoFactorAuthentication"
                        class="px-5 py-2.5 rounded-lg bg-primary-500 text-white text-sm font-medium hover:bg-primary-600 transition-colors">
                        Enable Two-Factor
                    </button>
                </div>
            @else
                <div class="space-y-4">
                    <p class="text-sm text-foreground font-medium">
                        You have enabled two-factor authentication.
                    </p>

                    @if ($showingQrCode)
                        <div class="space-y-4 mt-4">
                            <p class="text-sm text-foreground">
                                @if ($showingConfirmation)
                                    To finish enabling two-factor authentication, scan the following QR code using your phone's
                                    authenticator application or enter the setup key and provide the generated OTP code.
                                @else
                                    Two-factor authentication is now enabled. Scan the following QR code using your phone's
                                    authenticator application or enter the setup key.
                                @endif
                            </p>

                            <div class="mt-4 p-2 inline-block bg-white border border-gray-200 rounded-lg">
                                {!! $qrCodeSvg !!}
                            </div>

                            <div class="mt-4">
                                <p class="text-sm font-semibold">Setup Key: {{ $setupKey }}</p>
                            </div>

                            @if ($showingConfirmation)
                                <div class="mt-4">
                                    <label for="twoFactorCode" class="block text-sm font-medium text-foreground mb-1.5">Code</label>
                                    <input wire:model="twoFactorCode" type="text" id="twoFactorCode" inputmode="numeric" autofocus
                                        class="max-w-xs block w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 @error('twoFactorCode') border-red-400 focus:ring-red-400 @enderror">
                                    @error('twoFactorCode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="mt-4">
                                    <button wire:click="confirmTwoFactorAuthentication"
                                        class="px-5 py-2.5 rounded-lg bg-primary-500 text-white text-sm font-medium hover:bg-primary-600 transition-colors">
                                        Confirm
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($showingRecoveryCodes)
                        <div class="space-y-4 mt-4 max-w-xl">
                            <p class="text-sm text-foreground">
                                Store these recovery codes in a secure password manager. They can be used to recover access to your
                                account if your two-factor authentication device is lost.
                            </p>

                            <div class="grid gap-1 font-mono text-sm bg-gray-50 rounded-lg p-4 border border-gray-200">
                                @foreach ((array) $this->user->recoveryCodes() as $code)
                                    <div>{{ $code }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!$showingConfirmation)
                        <div class="flex flex-wrap items-center gap-3 mt-5">
                            @if ($showingRecoveryCodes)
                                <button wire:click="regenerateRecoveryCodes"
                                    class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium hover:bg-gray-50 transition-colors">
                                    Regenerate Recovery Codes
                                </button>
                            @elseif ($showingQrCode)
                                <button wire:click="showRecoveryCodes"
                                    class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium hover:bg-gray-50 transition-colors">
                                    Show Recovery Codes
                                </button>
                            @endif

                            <button wire:click="disableTwoFactorAuthentication"
                                class="px-4 py-2 rounded-lg border border-red-200 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                                Disable Two-Factor
                            </button>
                        </div>
                    @else
                        <div class="mt-4 flex gap-3">
                            <button wire:click="disableTwoFactorAuthentication"
                                class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- ══ TAB: Danger Zone ══════════════════════════════════════ --}}
    @if ($tab === 'danger')
        <div class="bg-card border border-red-200 rounded-xl p-6 space-y-5">
            <div>
                <h3 class="text-base font-semibold text-red-600">Delete Account</h3>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Permanently delete your account. This action cannot be undone and all your data will be lost.
                </p>
            </div>

            <form wire:submit.prevent="deleteAccount" class="space-y-4">
                <div>
                    <label for="delete_password" class="block text-sm font-medium text-foreground mb-1.5">
                        Confirm your password to continue
                    </label>
                    <input wire:model="current_password" type="password" id="delete_password"
                        autocomplete="current-password" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-foreground bg-transparent
                                                               placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500
                                                               @error('current_password') border-red-400 @enderror">
                    @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition-colors">
                        Delete my account
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>