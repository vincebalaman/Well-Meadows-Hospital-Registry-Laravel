<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                color-scheme: light;
            }

            body {
                background:
                    radial-gradient(circle at top left, rgba(34, 211, 238, 0.16), transparent 18%),
                    linear-gradient(135deg, #f8fbfd 0%, #eef8fb 52%, #f7fbfd 100%);
            }

            .welcome-shell {
                min-height: 100vh;
            }
        </style>
    </head>
    <body class="min-h-screen text-slate-900 antialiased">
        <div class="welcome-shell flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-md rounded-[1.5rem] border border-slate-200 bg-white/90 px-6 py-8 text-center shadow-[0_24px_80px_-40px_rgba(15,23,42,0.28)] backdrop-blur">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[1.1rem] bg-cyan-50">
                    <img src="/images/wellmeadows-logo.png" alt="Well Meadows Hospital Logo" class="block h-10 w-10" />
                </div>

                <h1 class="mt-5 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                    Well Meadows Hospital Registry
                </h1>

                <a href="{{ route('login') }}" class="mt-7 inline-flex w-full items-center justify-center rounded-xl bg-cyan-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700">
                    Log In
                </a>
            </div>
        </div>
    </body>
</html>
