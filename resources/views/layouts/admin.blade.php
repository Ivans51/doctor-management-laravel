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
    @vite('resources/js/app.js')
    @stack('scripts-top')
</head>
<body class="antialiased flex">

<x-admin.header-component></x-admin.header-component>
<x-admin.main-component></x-admin.main-component>
<x-admin.footer-component></x-admin.footer-component>

<script src="{{ Vite::asset('resources/js/home.js') }}"></script>
<script src="{{ Vite::asset('resources/js/modal.js') }}"></script>
<script src="{{ Vite::asset('resources/js/response-management.js') }}"></script>
@include('sweetalert::alert')
@stack('scripts-bottom')

</body>
</html>
