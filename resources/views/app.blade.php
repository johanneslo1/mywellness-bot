<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>Johannes Lohmann | Münster</title>
    <meta name="description" content="Webentwickler und Wirtschaftsinformatikstudent in Münster"/>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead

    @production
        @if(\Illuminate\Support\Str::contains(request()->getHost(), 'johannes-lohmann.de'))
            <script async src="https://logic-lemon.technikclou.com/script.js"
                    data-website-id="21e8ab08-d533-421b-8950-fbe89e9dd360"></script>
        @endif
    @endproduction
</head>
<body class="font-sans antialiased bg-gray-200">

<div class="text-center w-full fixed top-0 py-2 bg-yellow-300">
    Diese Seite ist ein <a href="https://github.com/johanneslo1/mywellness-bot/" class="text-yellow-900 underline" target="_blank">Open-Source-Projekt</a>
    und steht in keiner Verbindung zu <a href="https://mywellness.de/"
                                         class="text-yellow-900 underline">MyWellness.de</a>.
</div>

{{--<a class="fixed top-0 left-2 py-2" href="https://github.com/johanneslo1/mywellness-bot/" target="_blank"><i class="pi pi-github"></i></a>--}}
{{--<a class="fixed top-0 right-2 py-2" href="https://github.com/johanneslo1/mywellness-bot/" target="_blank"><i class="pi pi-github"></i></a>--}}

<div class="py-12">
    @inertia
</div>

@if(\Illuminate\Support\Str::contains(request()->getHost(), 'johannes-lohmann.de'))
    <div class="text-center w-full fixed bottom-0 py-2 z-0">
        <a href="https://johannes-lohmann.de/imprint" class="hover:underline" target="_blank">Impressum</a>
    </div>
@endif
</body>
</html>
