<x-guest-layout :layout="env('AUTH_LAYOUT')">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mobile Number & User Type -->
        <div class="mt-4 flex flex-col md:flex-row gap-4">
            <!-- Mobile Number -->
            <div class="flex-1">
                <x-input-label for="mobile" :value="__('Mobile Number')" />
                <x-text-input
                    id="mobile"
                    class="block mt-1 w-full"
                    type="text"
                    name="mobile"
                    :value="old('mobile')"
                    required
                    autocomplete="tel"
                />
                <x-input-error :messages="$errors->get('mobile')" class="mt-1" />
            </div>

            <!-- User Type -->
            <div class="flex-1">
                <x-input-label for="user_type" :value="__('User Type')" />
                <select
                    id="user_type"
                    name="user_type"
                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required
                >
                    <option value="" disabled {{ old('user_type') ? '' : 'selected' }}> Select User Type </option>
                    <option value="employee" {{ old('user_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="employer" {{ old('user_type') == 'employer' ? 'selected' : '' }}>Employer</option>
                </select>
                <x-input-error :messages="$errors->get('user_type')" class="mt-1" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex justify-start mt-3 text-sm auth-muted mb-2">
            Already have an account?
            <a class="auth-link font-semibold ms-1" href="{{ route('login') }}">
                {{ __('Log in') }}
            </a>
        </div>

        <div class="flex items-center justify-end">

            <x-primary-button class="my-3 w-full justify-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
