@extends('layouts.app')

@php
    $header = 'Welkom op het Dashboard';
@endphp

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-primary">
                    {{ __("Je bent ingelogd!") }}
                </div>
            </div>
        </div>
    </div>

@endsection
