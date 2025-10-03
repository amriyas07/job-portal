<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        // Layout
        $layout = env('AUTH_LAYOUT') ?? 'centered';
        $imageUrl = env('AUTH_SPLIT_IMAGE_URL') ? asset(env('AUTH_SPLIT_IMAGE_URL')) : asset('assets/auth/split-login.jpg');
    @endphp
    <body>
        @if($layout === 'split')
            <div class="auth-split grid grid-cols-1 md:grid-cols-2">
                <div class="order-2 md:order-1 hidden md:block">
                    <div class="auth-image-panel" style="background-image: url('{{ $imageUrl }}'); background-color: #f5f5f5;"></div>
                </div>

                <!-- Form Div -->
                <div class="order-1 md:order-2 auth-form-panel auth-bg">
                    <div class="w-full max-w-md mx-auto auth-card p-6 sm:p-8">
                        <div class="flex justify-center mb-4">
                            <a href="{{ route('home') }}">
                                <x-application-logo class="w-20 h-20" />
                            </a>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        @else
            <!-- Centered Layout -->
            <div class="auth-bg flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 auth-card shadow-md overflow-hidden">
                    <div class="flex justify-center mb-4">
                        <a href="/">
                            <x-application-logo class="w-20 h-20" />
                        </a>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        @endif
    </body>
</html>
