<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Doctor Management</title>
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/logo.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <!-- Styles -->
    @vite('resources/css/app.css')
    @stack('styles')

    <!-- Javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
    @vite('resources/js/app.js')
    @stack('scripts-top')
</head>
<body class="antialiased flex">

<x-home.header-component></x-home.header-component>
<x-home.main-component></x-home.main-component>
<x-home.footer-component></x-home.footer-component>

<script src="{{ Vite::asset('resources/js/home.js') }}"></script>
<script src="{{ Vite::asset('resources/js/modal.js') }}"></script>
<script src="{{ Vite::asset('resources/js/response-management.js') }}"></script>
<script src="{{ Vite::asset('resources/js/sweet-management.js') }}"></script>
<script src="{{ Vite::asset('resources/js/utils.js') }}"></script>
@stack('scripts-bottom')

</body>
</html>
