<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sandee Shop') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen relative overflow-hidden flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
         style="background: linear-gradient(180deg, #FDF3E7 0%, #FCE4EC 45%, #E3F2FD 100%);">

        <!-- ของตกแต่งลอยไปมา -->
        <div class="floaty" style="top:8%; left:8%;">⭐</div>
        <div class="floaty" style="top:15%; right:10%; animation-delay:1.5s;">☁️</div>
        <div class="floaty" style="bottom:18%; left:6%; animation-delay:0.8s;">🪐</div>
        <div class="floaty" style="bottom:28%; right:12%; animation-delay:2.2s;">✨</div>
        <div class="floaty" style="top:45%; left:4%; animation-delay:3s; font-size:1.4rem;">💖</div>

        <div class="relative z-10">
            <a href="/">
                <x-application-logo class="w-64 sm:w-72 h-auto drop-shadow-sm" />
            </a>
        </div>

        <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white/95 backdrop-blur shadow-xl overflow-hidden sm:rounded-2xl border border-pink-100">
            {{ $slot }}
        </div>
    </div>
</body>
</html>