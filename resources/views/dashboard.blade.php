@extends('layouts.app')

@php
    $header = 'Welkom op het Dashboard';
    use Illuminate\Support\Facades\Auth;
@endphp



@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-primary">
            Welkom terug, {{ Auth::user()->name }}!
        </h2>

        @if(Auth::user()->last_login_at)
            <p class="text-gray-600">
                Laatste login: {{ \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('d-m-Y H:i') }}
            </p>
        @else
            <p class="text-gray-600">Dit is je eerste login.</p>
        @endif
    </div>
@endsection
