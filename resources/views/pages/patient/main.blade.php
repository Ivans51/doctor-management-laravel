@extends('layouts.patient')

@section('content')
    <h1 class="font-bold text-2xl mb-1">Welcome, {{ Auth::user()->patient->name }}</h1>
    <p class="text-sm">Have a nice day at great work</p>
@endsection
