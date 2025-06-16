@extends('layouts.app')

@php
    $header = 'Formulier bewerken';
@endphp

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-primary">Formulier "{{ $form->title }}" bewerken</h1>

    <div id="grade-levels" data-levels='@json($gradeLevels)' style="display: none;"></div>

    <form action="{{ route('forms.update', $form) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titel</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $form->title) }}"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Vak</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject', $form->subject) }}"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="oe_code" class="block text-sm font-medium text-gray-700 mb-1">OE-code</label>
                    <input
                        type="text"
                        id="oe_code"
                        name="oe_code"
                        value="{{ old('oe_code', $form->oe_code) }}"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Beschrijving</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                    >{{ old('description', $form->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Competenties Container --}}
        <div id="competencies-container" class="space-y-8 mt-6">
            @foreach ($form->formCompetencies as $cIndex => $fc)
                @php $competency = $fc->competency; @endphp

                <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200" data-competency-index="{{ $cIndex }}">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">Competentie {{ $cIndex + 1 }}</h2>
                        <button type="button" onclick="removeCompetency(this)" class="text-red-600 hover:text-red-800 text-2xl font-extrabold">✗</button>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                            <input
                                type="text"
                                name="competencies[{{ $cIndex }}][name]"
                                value="{{ old("competencies.$cIndex.name", $competency->name) }}"
                                class="block w-1/2 border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Domeinbeschrijving</label>
                                <textarea
                                    name="competencies[{{ $cIndex }}][domain_description]"
                                    rows="5"
                                    class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    required
                                >{{ old("competencies.$cIndex.domain_description", $competency->domain_description) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Beoordelingsschaal</label>
                                <textarea
                                    name="competencies[{{ $cIndex }}][rating_scale]"
                                    rows="5"
                                    class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    required
                                >{{ old("competencies.$cIndex.rating_scale", $competency->rating_scale) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Knock-out Criteria & Deliverables</label>
                                <textarea
                                    name="competencies[{{ $cIndex }}][complexity]"
                                    rows="5"
                                    class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    required
                                >{{ old("competencies.$cIndex.complexity", $competency->complexity) }}</textarea>
                            </div>
                        </div>

                        <div id="competency-{{ $cIndex }}-components" class="space-y-6">
                            @foreach ($competency->components as $compIndex => $component)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 space-y-6" data-component-index="{{ $compIndex }}">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-800">Component {{ $compIndex + 1 }}</h3>
                                        <button type="button" onclick="removeComponent(this)" class="text-red-600 hover:text-red-800 text-2xl font-extrabold">✗</button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Naam</label>
                                            <input
                                                type="text"
                                                name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][name]"
                                                value="{{ old("competencies.$cIndex.components.$compIndex.name", $component->name) }}"
                                                class="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                required
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Beschrijving</label>
                                            <textarea
                                                name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][description]"
                                                rows="4"
                                                class="block text-sm w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                                                required
                                            >{{ old("competencies.$cIndex.components.$compIndex.description", $component->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        @foreach(['onvoldoende','voldoende','goed'] as $lIndex => $grade)
                                            @foreach($component->levels as $level)
                                                @if(strtolower($level->gradeLevel->name) === $grade)
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                            {{ ucfirst($grade) }} ({{ $level->gradeLevel->points }} pt)
                                                        </label>
                                                        <input
                                                            type="hidden"
                                                            name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][levels][{{ $lIndex }}][grade_level_id]"
                                                            value="{{ $level->grade_level_id }}"
                                                        >
                                                        <textarea
                                                            name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][levels][{{ $lIndex }}][description]"
                                                            class="block text-sm w-full min-h-24 border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                                                            rows="2"
                                                        >{{ old("competencies.$cIndex.components.$compIndex.levels.$lIndex.description", optional($level)->description) }}</textarea>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <x-secondary-button
                                type="button"
                                class="mt-4 bg-blue-600 hover:bg-blue-700"
                                onclick="addComponent({{ $cIndex }})">
                                + Component toevoegen
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button
                type="button"
                class="mr-auto bg-green-600 hover:bg-green-700"
                onclick="addCompetency()">
                + Competentie toevoegen
            </x-secondary-button>

            <x-primary-button type="submit">Wijzigingen opslaan</x-primary-button>
        </div>
    </form>
@endsection
