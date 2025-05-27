@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">{{ $form->title }}</h1>
        <p class="mb-2"><strong>Onderwerp:</strong> {{ $form->subject }}</p>
        <p class="mb-4"><strong>Beschrijving:</strong> {{ $form->description }}</p>

        <x-primary-button>
            <a href="{{ route('forms.edit', $form) }}">Bewerk</a>
        </x-primary-button>
        <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline" onsubmit="return confirm('Weet je het zeker?');">
            @csrf
            @method('DELETE')
            <x-primary-button type="submit">Verwijder</x-primary-button>
        </form>

        <h2 class="text-xl font-semibold mt-6 mb-2">Competenties</h2>
        @foreach($form->formCompetencies as $fc)
            <div class="mb-4 p-4 border rounded">
                <h3 class="font-semibold">{{ $fc->competency->name }}</h3>
                <p><em>{{ $fc->competency->domain_description }}</em></p>
                <div class="mt-2 space-y-3">
                    @foreach($fc->competency->components as $component)
                        <div class="pl-4">
                            <h4 class="font-medium">Component: {{ $component->name }}</h4>
                            <p>{{ $component->description }}</p>
                            <ul class="list-disc list-inside mt-1">
                                @foreach($component->levels as $level)
                                    <li>({{ $level->gradeLevel->points }} pts) {{ $level->description }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <a href="{{ route('filled_forms.index') }}" class="mt-6 inline-block text-blue-500 hover:underline">Terug naar lijst</a>
    </div>
@endsection

