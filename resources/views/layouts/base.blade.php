<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- META TAGS --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

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
    <div id="app">@yield('content')</div>

    @include('components.loading')
    @include('components.alert')
</body>
</html>
