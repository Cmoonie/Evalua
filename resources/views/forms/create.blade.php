@extends('layouts.app')

@section('title', 'Nieuw formulier aanmaken')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Nieuw beoordelingsformulier</h1>

        <form action="{{ route('forms.store') }}" method="POST">
            @csrf

                        {{-- Algemene formuliergegevens --}}
                        <div class="mb-4">
                            <label for="title" class="block font-semibold">Titel</label>
                            <input type="text" id="title" name="title" class="w-full p-2 border rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="block font-semibold">Vak</label>
                            <input type="text" id="subject" name="subject" class="w-full p-2 border rounded" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-semibold">Beschrijving</label>
                            <textarea id="description" name="description" rows="3" class="w-full p-2 border rounded"></textarea>
                        </div>

                        {{-- Competenties en componenten --}}
                        @foreach (range(0, 1) as $cIndex)
                            <div class="border p-4 rounded mb-6 bg-gray-50">
                                <h2 class="text-xl font-semibold mb-2">Competentie {{ $cIndex + 1 }}</h2>

                                <div class="mb-2">
                                    <label class="block font-medium">Naam</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][name]" class="w-full border p-2 rounded">
                                </div>

                                <div class="mb-2">
                                    <label class="block font-medium">Domeinbeschrijving</label>
                                    <textarea name="competencies[{{ $cIndex }}][domain_description]" class="w-full border p-2 rounded"></textarea>
                                </div>

                                <div class="mb-2">
                                    <label class="block font-medium">Rating scale</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][rating_scale]" class="w-full border p-2 rounded">
                                </div>

                                <div class="mb-2">
                                    <label class="block font-medium">Complexiteit</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][complexity]" class="w-full border p-2 rounded">
                                </div>

                                {{-- Componenten --}}
                                @foreach (range(0, 1) as $compIndex)
                                    <div class="border-l-4 border-primary pl-4 mt-4 mb-4">
                                        <h3 class="font-semibold">Component {{ $compIndex + 1 }}</h3>

                                        <div class="mb-2">
                                            <label class="block">Naam</label>
                                            <input type="text" name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][name]" class="w-full border p-2 rounded">
                                        </div>

                                        <div class="mb-2">
                                            <label class="block">Beschrijving</label>
                                            <textarea name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][description]" class="w-full border p-2 rounded"></textarea>
                                        </div>

                                        {{-- Beoordelingsniveaus --}}
                                        @foreach ($gradeLevels as $level)
                                            <div class="mb-2 ml-4">
                                                <label class="block font-medium">Beschrijving voor "{{ $level->name }}" ({{ $level->points }} pts)</label>
                                                <input type="hidden" name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][levels][{{ $loop->index }}][grade_level_id]" value="{{ $level->id }}">
                                                <textarea name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][levels][{{ $loop->index }}][description]" class="w-full border p-2 rounded"></textarea>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Submit knop --}}
                        <x-primary-button>
                            Formulier opslaan
                        </x-primary-button>
                    </form>
                </div>
@endsection

