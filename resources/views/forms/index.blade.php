@extends('layouts.app')

@section('title', 'Formulieren')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Beschikbare formulieren</h1>

    <a href="{{ route('forms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nieuw formulier</a>

    <ul class="mt-6 space-y-2">
        <li class="p-4 bg-white rounded shadow">Voorbeeldformulier 1</li>
        <li class="p-4 bg-white rounded shadow">Voorbeeldformulier 2</li>
    </ul>
@endsection
