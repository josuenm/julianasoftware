<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- META TAGS --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSS --}}
    @stack('head-styles')
    @vite('resources/css/app.css')

    {{-- Scripts JS --}}
    <script defer src="{{@asset('js/jquery.js')}}"></script>
    <script defer src="{{@asset('js/app.js')}}"></script>
    @stack('head-scripts')

    <title>@yield('title')</title>
</head>
<body>
    @yield('content')
</body>
</html>
