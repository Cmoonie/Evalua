@extends('layouts.app')

@php
    $header = 'Welkom op het Dashboard';
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
@endphp



@section('content')
    <x-secondary-button id="help-dashboard-button">
        Begin rondleiding
    </x-secondary-button>

    <div class="bg-white shadow-sm rounded-lg mt-2 p-6 mb-6">
        <h2 class="text-2xl font-bold text-primary">
            Welkom terug, {{ Auth::user()->name }}!
        </h2>

        @php
            $user = \App\Models\User::find(auth()->id());
        @endphp

        @if($user->previous_login_at)
            <p class="text-gray-600">
                Laatste login: {{ Carbon::parse($user->previous_login_at)->format('d-m-Y H:i') }}
            </p>
        @else
            <p class="text-gray-600">
                Welkom! Dit is je eerste login.
            </p>
        @endif

    </div>
@endsection
