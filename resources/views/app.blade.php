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
        @if(\Illuminate\Support\Str::contains('johannes-lohmann.de', request()->getHost()))
            <script async src="https://logic-lemon.technikclou.com/script.js"
                    data-website-id="21e8ab08-d533-421b-8950-fbe89e9dd360"></script>
        @endif
    @endproduction
    </head>
    <body class="font-sans antialiased">

    <div class="text-center w-full fixed top-0 py-2 bg-yellow-300">
        Diese Seite ist ein Open-Source-Projekt und steht in keiner Verbindung zu <a href="https://mywellness.de/" target="_blank" class="text-yellow-900 underline">MyWellness.de</a>.
    </div>

        @inertia
    </body>
</html>
