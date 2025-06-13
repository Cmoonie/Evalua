@extends('layouts.app')

@section('title', 'Nieuw formulier aanmaken')

@section('content')
        <h1 class="text-3xl font-bold mb-6">Nieuw beoordelingsformulier</h1>
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
                            <input type="text"
                                   id="subject"
                                   name="subject"
                                   class="w-full p-2 border rounded"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="oe_code" class="block font-semibold">OE-code</label>
                            <input
                                type="text"
                                name="oe_code"
                                id="oe_code"
                                class="w-full p-2 border rounded"
                                required>
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
                            <button type="button" class="text-red-700 hover:text-red-800 font-bold text-xl transition" @click="removeCompetency(cIndex)">✖</button>
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
                            <label class="block font-medium">Rating scale</label>
                            <input type="text" :name="`competencies[${cIndex}][rating_scale]`" class="w-full border p-2 rounded">
                        </div>
                                <div class="mb-2">
                                    <label class="block font-medium">Beoordelingsschaal</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][rating_scale]" class="w-full border p-2 rounded">
                                </div>

                        <div class="mb-2">
                            <label class="block font-medium">Complexiteit</label>
                            <input type="text" :name="`competencies[${cIndex}][complexity]`" class="w-full border p-2 rounded">
                        </div>
                                <div class="mb-2">
                                    <label class="block font-medium">Knock-out Criteria & Deliverables</label>
                                    <input type="text" name="competencies[{{ $cIndex }}][complexity]" class="w-full border p-2 rounded">
                                </div>

                        {{-- Componenten --}}
                        <template x-for="(component, compIndex) in competency.components" :key="component.id">
                            <div class="border-l-4 border-primary pl-4 mt-4 mb-4 bg-white p-4 rounded">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-semibold mb-2">Component <span x-text="compIndex + 1"></span></h3>
                                    <button type="button" class="text-red-500 font-bold" @click="removeComponent(cIndex, compIndex)">✖</button>
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
                                        <label class="block font-medium">Beschrijving voor "{{ $level->name }}" ({{ $level->points }} pts)</label>
                                        <input type="hidden" :name="`competencies[${cIndex}][components][${compIndex}][levels][{{ $loop->index }}][grade_level_id]`" value="{{ $level->id }}">
                                        <textarea :name="`competencies[${cIndex}][components][${compIndex}][levels][{{ $loop->index }}][description]`" class="w-full border p-2 rounded"></textarea>
                                    </div>
                                @endforeach
                            </div>
                        </template>

                        <x-primary-button type="button" class="mt-2" @click="addComponent(cIndex)">
                            + Component toevoegen
                        </x-primary-button>
                    </div>
                </template>

                        {{-- Submit knop --}}
                        <x-primary-button type="submit">
                            Formulier opslaan
                        </x-primary-button>
                    </form>
@endsection
                <x-primary-button type="button" class="mb-6" @click="addCompetency()">
                    + Competentie toevoegen
                </x-primary-button>

                <x-primary-button>
                    Formulier opslaan
                </x-primary-button>
            </form>
        </div>

        <script>
            function formBuilder() {
                return {
                    competencies: [
                        {
                            id: Date.now(),
                            components: [
                                { id: Date.now() + 1 },
                                { id: Date.now() + 2 }
                            ]
                        },
                        {
                            id: Date.now() + 3,
                            components: [
                                { id: Date.now() + 4 },
                                { id: Date.now() + 5 }
                            ]
                        }
                    ],
                    addCompetency() {
                        this.competencies.push({
                            id: Date.now() + Math.random(),
                            components: [
                                { id: Date.now() + Math.random() }
                            ]
                        });
                    },
                    removeCompetency(index) {
                        this.competencies.splice(index, 1);
                    },
                    addComponent(competencyIndex) {
                        this.competencies[competencyIndex].components.push({
                            id: Date.now() + Math.random()
                        });
                    },
                    removeComponent(competencyIndex, compIndex) {
                        this.competencies[competencyIndex].components.splice(compIndex, 1);
                    }
                };
            }
        </script>
    @endsection

