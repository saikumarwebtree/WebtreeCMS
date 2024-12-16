<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @stack('styles')
</head>
<body>
    @include('theme::partials.header')
    <main>
        @yield('content')
    </main>
    @include('theme::partials.footer')
    @stack('scripts')
</body>
</html>