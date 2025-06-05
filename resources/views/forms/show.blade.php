@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $form->title }}</h1>
        <p class="mb-2"><strong>Onderwerp:</strong> {{ $form->subject }}</p>
        <p class="mb-4"><strong>Beschrijving:</strong> {{ $form->description }}</p>

        <div class="flex gap-4 mt-4">
            <x-secondary-button>
                <a href="{{ route('forms.edit', $form) }}">Bewerk</a>
            </x-secondary-button>
            <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline" onsubmit="return confirm('Weet je het zeker?');">
                @csrf
                @method('DELETE')
                <x-secondary-button type="submit">Verwijder</x-secondary-button>
            </form>
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-4">Competenties</h2>

        @foreach($form->formCompetencies as $index => $fc)
            <div x-data="{ open: false }" class="mb-6 border rounded-lg shadow-sm">
                <button
                    @click="open = !open"
                    class="w-full text-left bg-primary text-white px-4 py-3 rounded-t-lg font-semibold flex justify-between items-center">
                    <span>Competentie {{ $index + 1 }}: {{ $fc->competency->name }}</span>
                    <svg :class="{'rotate-180': open}" class="w-5 h-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" class="p-4 bg-gray-50">
                    <div class="mb-3">
                        <p><span class="bg-secondary text-white px-2 py-1 rounded font-semibold">Domeinbeschrijving:</span> <em>{{ $fc->competency->domain_description }}</em></p>
                    </div>
                    <div class="mb-3">
                        <p><span class="bg-secondary text-white px-2 py-1 rounded font-semibold">Beoordelingsschaal:</span> {{ $fc->competency->rating_scale }}</p>
                    </div>
                    <div class="mb-3">
                        <p><span class="bg-secondary text-white px-2 py-1 rounded font-semibold">Knock-out Criteria & Deliverables:</span> {{ $fc->competency->complexity }}</p>
                    </div>

                    @foreach($fc->competency->components as $component)
                        <div class="pl-4 mb-4">
                            <h4 class="mb-2 bg-windy text-white px-2 py-1 font-semibold">Component: {{ $component->name }}</h4>
                            <p class=" bg-tertiary text-white px-2 ">Beschrijving : {{ $component->description }}</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach($component->levels as $level)
                                    <li><strong>({{ $level->gradeLevel->points }} pts)</strong> {{ $level->description }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <a href="{{ route('forms.index') }}"><x-primary-button>
                Terug naar lijst
            </x-primary-button>
        </a>
    </div>
@endsection

