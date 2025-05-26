@extends('layouts.app')

@php
    $header = 'Alle formulieren';
@endphp

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Alle Formulieren</h1>
            <a href="{{ route('forms.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Nieuw Formulier</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($forms->isEmpty())
            <p>Geen formulieren gevonden. Tijd om er een te maken!</p>
        @else
            <table class="w-full bg-white shadow rounded">
                <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left">Titel</th>
                    <th class="p-3 text-left">Vak</th>
                    <th class="p-3 text-left">Aangemaakt op</th>
                </tr>
                </thead>
                <tbody>
                @foreach($forms as $form)
                    <tr class="border-t">
                        <td class="p-3"><a href="{{ route('forms.show', $form) }}" class="text-primary hover:underline">{{ $form->title }}</a></td>
                        <td class="p-3">{{ $form->subject }}</td>
                        <td class="p-3">{{ $form->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
