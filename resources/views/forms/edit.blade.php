@extends('layouts.app')

@section('title', 'Formulier bewerken')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Formulier bewerken</h1>

        <form action="{{ route('forms.update', $form) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="title" class="block font-semibold mb-1">Titel</label>
                <input type="text" id="title" name="title" value="{{ old('title', $form->title) }}" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-6">
                <label for="subject" class="block font-semibold mb-1">Vak</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject', $form->subject) }}" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-6">
                <label for="description" class="block font-semibold mb-1">Beschrijving</label>
                <textarea id="description" name="description" rows="3" class="w-full p-2 border rounded">{{ old('description', $form->description) }}</textarea>
            </div>

            @foreach ($form->formCompetencies as $cIndex => $fc)
                <div class="mb-8 border p-4 rounded bg-gray-50">
                    <h2 class="text-xl font-bold mb-4">Competentie {{ $cIndex + 1 }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium">Naam</label>
                            <input type="text" name="competencies[{{ $cIndex }}][name]" value="{{ $fc->competency->name }}" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block font-medium">Domeinbeschrijving</label>
                            <textarea name="competencies[{{ $cIndex }}][domain_description]" class="w-full border p-2 rounded">{{ $fc->competency->domain_description }}</textarea>
                        </div>
                        <div>
                            <label class="block font-medium">Beoordelingsschaal</label>
                            <input type="text" name="competencies[{{ $cIndex }}][rating_scale]" value="{{ $fc->competency->rating_scale }}" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block font-medium">Knock-out Criteria & Deliverables</label>
                            <input type="text" name="competencies[{{ $cIndex }}][complexity]" value="{{ $fc->competency->complexity }}" class="w-full border p-2 rounded">
                        </div>
                    </div>

                    <div id="competency-{{ $cIndex }}-components">
                        @foreach ($fc->competency->components as $compIndex => $component)
                            <div class="pl-4 mt-6 mb-6 border-l-4 border-primary bg-white p-4 rounded">
                                <h3 class="font-semibold text-lg mb-2">Component {{ $compIndex + 1 }}</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block">Naam</label>
                                        <input type="text" name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][name]" value="{{ $component->name }}" class="w-full border p-2 rounded">
                                    </div>
                                    <div>
                                        <label class="block">Beschrijving</label>
                                        <textarea name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][description]" class="w-full border p-2 rounded">{{ $component->description }}</textarea>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    @foreach ($component->levels as $levelIndex => $level)
                                        <div>
                                            <label class="block font-medium">Niveau: {{ $level->gradeLevel->name }} ({{ $level->gradeLevel->points }} pt)</label>
                                            <input type="hidden" name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][levels][{{ $levelIndex }}][grade_level_id]" value="{{ $level->grade_level_id }}">
                                            <textarea name="competencies[{{ $cIndex }}][components][{{ $compIndex }}][levels][{{ $levelIndex }}][description]" class="w-full border p-2 rounded">{{ $level->description }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <x-primary-button type="button" onclick="addComponent({{ $cIndex }})" >
                        + Component toevoegen
                    </x-primary-button>
                </div>
            @endforeach

            <div class="mt-8">
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
                    Wijzigingen opslaan
                </button>
            </div>
        </form>
    </div>

    <script>
        const gradeLevels = @json($gradeLevels);

        function addComponent(competencyIndex) {
            const container = document.querySelector(`#competency-${competencyIndex}-components`);
            const count = container.children.length;

            let html = `
                <div class="pl-4 mt-6 mb-6 border-l-4 border-primary bg-white p-4 rounded">
                    <h3 class="font-semibold text-lg mb-2">Component ${count + 1}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block">Naam</label>
                            <input type="text" name="competencies[${competencyIndex}][components][${count}][name]" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block">Beschrijving</label>
                            <textarea name="competencies[${competencyIndex}][components][${count}][description]" class="w-full border p-2 rounded"></textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            `;

            gradeLevels.forEach((level, i) => {
                html += `
                    <div>
                        <label class="block font-medium">Niveau: ${level.name} (${level.points} pt)</label>
                        <input type="hidden" name="competencies[${competencyIndex}][components][${count}][levels][${i}][grade_level_id]" value="${level.id}">
                        <textarea name="competencies[${competencyIndex}][components][${count}][levels][${i}][description]" class="w-full border p-2 rounded"></textarea>
                    </div>
                `;
            });

            html += `</div></div>`;

            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
@endsection


