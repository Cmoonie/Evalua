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

{{--        @if(Auth::user()->last_login_at)--}}
{{--            <p class="text-gray-600">--}}
{{--                Laatste login: {{ \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('d-m-Y H:i') }}--}}
{{--            </p>--}}
{{--        @else--}}

{{--            <p class="text-gray-600">Dit is je eerste login.</p>--}}
{{--        @endif--}}

{{--        @if(Auth::user()->previous_login_at)--}}
{{--            <p class="text-gray-600">--}}
{{--                Laatste login: {{ \Carbon\Carbon::parse(Auth::user()->previous_login_at)->format('d-m-Y H:i') }}--}}
{{--            </p>--}}
{{--        @else--}}
{{--            <p class="text-gray-600">--}}
{{--                Welkom! Dit is je eerste login.--}}
{{--            </p>--}}
{{--        @endif--}}

        @php
            $user = \App\Models\User::find(auth()->id());
        @endphp

        @if($user->previous_login_at)
            <p class="text-gray-600">
                Laatste login: {{ \Carbon\Carbon::parse($user->previous_login_at)->format('d-m-Y H:i') }}
            </p>
        @else
            <p class="text-gray-600">
                Welkom! Dit is je eerste login.
            </p>
        @endif

        <script>
            function startIntro() {
                // Zet in de sessie dat we de rondleiding moeten starten
                sessionStorage.setItem('startFormIntro', 'true');
                window.location.href = "{{ route('forms.index') }}";
            }
        </script>
        <x-primary-button onclick="startIntroDashboard()">
            Wat kan ik hier
      </x-primary-button>

    </div>
@endsection
