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
<body class="font-sans text-gray-900 antialiased">
    @include('sweetalert::alert')

    <!-- Cloud Background -->
    

    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-flasy pt-6 sm:pt-0">
        <div>
            <a href="/" wire:navigate>
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg glass">
            {{ $slot }}
        </div>
        {{-- <div class="cloud-container">
            <div class="sky">
                <div class="cloud" id="cloud">
                    <div class="cloud-part" id="cloud-back"></div>
                    <div class="cloud-part" id="cloud-mid"></div>
                    <div class="cloud-part" id="cloud-front"></div>
    
                    <svg width="0" height="0"> 
                        <filter id="filter-back">
                            <feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="4" seed="0" />     
                            <feDisplacementMap in="SourceGraphic" scale="170" />
                        </filter>
                        <filter id="filter-mid">
                            <feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="2" seed="0"/>
                            <feDisplacementMap in="SourceGraphic" scale="150" />
                        </filter>
                        <filter id="filter-front">
                            <feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="2" seed="0"/>
                            <feDisplacementMap in="SourceGraphic" scale="50" />
                        </filter>
                    </svg>
                </div>
            </div>
        </div> --}}
        
    </div>
</body>
</html>
