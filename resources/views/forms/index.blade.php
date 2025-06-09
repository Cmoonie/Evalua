@extends('layouts.app')

@php
    $header = 'Alle formulieren';
@endphp

@section('content')
    <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
        <h1 class="text-2xl font-bold">Alle Formulieren</h1>

        <div class="flex gap-2">
            <x-primary-button onclick="startIntroForms()">
                Uitleg over deze pagina
            </x-primary-button>
            <x-link-button id="new-form-button" href="{{ route('forms.create') }}">
                Nieuw Formulier
            </x-link-button>
        </div>
    </div>


    @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($forms->isEmpty())
            <p>Geen formulieren gevonden. Tijd om er een te maken! </p>
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


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (sessionStorage.getItem('startFormIntro') === 'true') {
                sessionStorage.removeItem('startFormIntro'); // Eenmalig uitvoeren
                setTimeout(() => {
                    if (typeof window.startIntro === 'function') {
                        window.startIntro();
                    } else {
                        console.warn("startIntro niet gevonden.");
                    }
                }, 300); // Kleine vertraging voor de zekerheid
            }
        });
    </script>
@endpush

