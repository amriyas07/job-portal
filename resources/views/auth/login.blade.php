<x-guest-layout :layout="env('AUTH_LAYOUT')">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mt-4">
            <!-- Remember Me -->
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="auth-checkbox" name="remember">
                <span class="ms-2 text-sm auth-muted">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link text-sm" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
        <div class="flex justify-start mt-4 mb-3">
            <span class="text-sm auth-muted">
                Don't have an account?
                <a class="auth-link text-sm font-semibold" href="{{ route('register') }}">
                    {{ __('Sign up') }}
                </a>
            </span>
        </div>
        <div>
            <x-primary-button class="my-3 w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
