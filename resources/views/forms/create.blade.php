@extends('layouts.app')

@php
    $header = 'Nieuw formulier aanmaken';
@endphp

@section('content')
    <div class="container mx-auto p-6" x-data="formBuilder()">
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
                <label for="oe_code" class="block font-semibold">OE-code</label>
                <input type="text" id="oe_code" name="oe_code" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block font-semibold">Beschrijving</label>
                <textarea id="description" name="description" rows="3" class="w-full p-2 border rounded"></textarea>
            </div>

            {{-- Dynamische competenties --}}
            <template x-for="(competency, cIndex) in competencies" :key="competency.id">
                <div class="border p-4 rounded mb-6 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-semibold">Competentie <span x-text="cIndex + 1"></span></h2>
                        <button type="button" @click="removeCompetency(cIndex)" class="text-red-500 font-extrabold text-2xl hover:text-red-800">✗</button>
                    </div>

                    <div class="mb-2">
                        <label class="block font-medium">Naam</label>
                        <input type="text" :name="`competencies[${cIndex}][name]`" class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-2">
                        <label class="block font-medium">Domeinbeschrijving</label>
                        <textarea :name="`competencies[${cIndex}][domain_description]`" class="w-full border p-2 rounded"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="block font-medium">Beoordelingsschaal</label>
                        <input type="text" :name="`competencies[${cIndex}][rating_scale]`" class="w-full border p-2 rounded">
                    </div>

                    <div class="mb-2">
                        <label class="block font-medium">Knock-out Criteria & Deliverables</label>
                        <input type="text" :name="`competencies[${cIndex}][complexity]`" class="w-full border p-2 rounded">
                    </div>

                    {{-- Componenten --}}
                    <template x-for="(component, compIndex) in competency.components" :key="component.id">
                        <div class="border-l-4 border-primary pl-4 mt-4 mb-4 bg-white p-4 rounded">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold mb-2">Component <span x-text="compIndex + 1"></span></h3>
                                <button type="button" @click="removeComponent(cIndex, compIndex)" class="text-red-500 font-extrabold text-2xl hover:text-red-800">✗</button>
                            </div>

                            <div class="mb-2">
                                <label class="block">Naam</label>
                                <input type="text" :name="`competencies[${cIndex}][components][${compIndex}][name]`" class="w-full border p-2 rounded">
                            </div>

                            <div class="mb-2">
                                <label class="block">Beschrijving</label>
                                <textarea :name="`competencies[${cIndex}][components][${compIndex}][description]`" class="w-full border p-2 rounded"></textarea>
                            </div>

                            @foreach ($gradeLevels as $level)
                                <div class="mb-2 ml-4">
                                    <label class="block font-medium">
                                        Beschrijving voor "{{ $level->name }}" ({{ $level->points }} pts)
                                    </label>
                                    <input type="hidden" :name="`competencies[${cIndex}][components][${compIndex}][levels][${{ $loop->index }}][grade_level_id]`" value="{{ $level->id }}">
                                    <textarea :name="`competencies[${cIndex}][components][${compIndex}][levels][${{ $loop->index }}][description]`" class="w-full border p-2 rounded"></textarea>
                                </div>
                            @endforeach
                        </div>
                    </template>

                    <x-secondary-button @click="addComponent(cIndex)">+ Component toevoegen</x-secondary-button>
                </div>
            </template>

                <x-secondary-button @click="addCompetency()">+ Competentie toevoegen</x-secondary-button>
            <br>

                <x-primary-button>Formulier opslaan</x-primary-button>

        </form>
    </div>
@endsection
