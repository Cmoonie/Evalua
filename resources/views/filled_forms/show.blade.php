@extends('layouts.app')

@php $header = 'Beoordeling bekijken'; @endphp

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex flex-wrap justify-between lg:flex-nowrap ">
            <div>
                <h1 class="text-2xl text-primary font-bold mb-4">Beoordeling van {{ $filledForm->student_name }}</h1>
                <p class="mb-4"><strong>Titel:</strong> {{ $filledForm->form->title }}</p>
                <p class="mb-4"><strong>Vak:</strong> {{ $filledForm->form->subject }}</p>
                <p class="mb-4"><strong>Beschrijving:</strong> {{ $filledForm->form->description }}</p>
                <p class="mb-4"><strong>Datum ingevuld:</strong> {{ $filledForm->created_at->format('Y-m-d H:i') }}</p>
                @if($filledForm->created_at->ne($filledForm->updated_at))
                    <p class="mb-4"><strong>Datum aangepast:</strong> {{ $filledForm->updated_at->format('Y-m-d H:i') }}</p>
                @endif
            </div>

            <div class="mb-8">
                <table class="mb-4 table-auto border-collapse">
                    <thead>
                    <tr class="bg-gray-200 text-primary">
                        <th class="p-2 text-left">Eindbeoordeling</th>
                        <th class="p-2 text-center">Totaal te behalen punten</th>
                        <th class="p-2 text-center">Behaald</th>
                        <th class="p-2 text-center">Minimale punten eis</th>
                        <th class="p-2 text-center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($competencies as $comp)
                        <tr class="border-b {{ $comp['stateClass'] }}">
                            <td class="p-2">Comp. {{ $comp['name'] }}</td>
                            <td class="p-2 text-center"> 25 </td>
                            <td class="p-2 text-center"> {{ $comp['total'] }} </td>
                            <td class="p-2 text-center">12</td>
                            <td class="p-2 text-center"> {{ $comp['statusText'] }} </td>
                        </tr>
                    @endforeach
                        <tr class="bg-gray-200 font-semibold text-primary">
                            <td class="p-2 text-start">Cijfer: {{ $finalGrade }}</td>
                            <td class="p-2 text-center"> 150 </td>
                            <td class="p-2 text-center"> {{ $grandTotal }} </td>
                            <td class="p-2 text-center">72</td>
                            <td class="p-2 text-center"> {{ $finalStatus }} </td>
                        </tr>
                    </tbody>
                </table>
                <p class="mb-4  text-sm text-primary">
                    <strong>Toelichting: </strong>
                    <11 = Onvoldoende || 12 - 16 = Voldoende || 17 - 25 = Goed. Max 1 onvoldoende per competentie.
                    <br>
                    Bij twee of meer onvoldoendes in één competentie wordt de competentie automatisch onvoldoende.
                    <br>
                    Bij twee onvoldoende competenties is het maximaal te behalen cijfer een 5,0.
                </p>
            </div>
        </div>

        @foreach ($competencies as $comp)
            <div class="mb-6" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="bg-primary hover:bg-secondary py-2 px-4 text-xl font-bold text-white shadow-lg mb-2
                           flex items-center justify-between w-full rounded-lg transition-colors duration-300">
                    <span>Competentie: {{ $comp['name'] }}</span>
                    <div class="flex items-center">
                        <span class="text-sm mr-2">{{ $comp['statusText'] }}: {{ $comp['total'] }} pts</span>
                        <svg :class="{'transform rotate-180': open}"
                             xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor"
                             class="w-6 h-6 transition-transform duration-300">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                <div x-show="open" x-transition">
                    <div class="p-4 border border-gray-200 bg-white rounded-lg">
                        <table class="w-full table-auto border-collapse mb-2">
                            <thead>
                            <tr class="bg-gray-200 text-primary">
                                <th class="p-2 text-left">Component</th>
                                <th class="p-2 text-center">Onvoldoende (0)</th>
                                <th class="p-2 text-center">Voldoende (3)</th>
                                <th class="p-2 text-center">Goed (5)</th>
                                <th class="p-2 text-center">Punten</th>
                                <th class="p-2">Opmerking</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($comp['components'] as $c)
                                <tr class="border-b">
                                    <td class="p-2 text-secondary"><strong>{{ $c['name'] }}</strong>
                                    </td>
                                    <td class="p-2 font-bold text-xl text-red-500 text-center">
                                        {{ $c['points'] === 0 ? '✗' : '' }}
                                    </td>
                                    <td class="p-2 font-bold text-xl text-yellow-500 text-center">
                                        {{ $c['points'] === 3 ? '✓' : '' }}
                                    </td>
                                    <td class="p-2 font-bold text-xl text-green-500 text-center">
                                        {{ $c['points'] === 5 ? '✓' : '' }}
                                    </td>
                                    <td class="p-2 text-center">{{ $c['points'] }}</td>
                                    <td class="p-2 text-center">{{ $c['comment'] }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100 text-primary font-bold">
                                <td colspan="4" class="p-2 text-right">Totaal</td>
                                <td class="p-2 text-center">{{ $comp['total'] }}</td>
                                <td class="p-2 text-center">{{ $comp['statusText'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mt-8">
                        <x-info-card :title="'Complexiteit'">
                            <p>
                                {{ $comp['complexity'] }}
                            </p>
                        </x-info-card>
                        <x-info-card :title="'Beoordelingsschaal'">
                            <p>
                                {{ $comp['rating_scale'] }}
                            </p>
                        </x-info-card>
                        <x-info-card :title="'Domeinbeschrijving'">
                            <p>
                                {{ $comp['domain_description'] }}
                            </p>
                        </x-info-card>
                    </div>

                </div>
            </div>
        @endforeach

        <x-primary-button>
            <a href="{{ route('filled_forms.edit', $filledForm) }}">Bewerk</a>
        </x-primary-button>

        <form action="{{ route('filled_forms.destroy', $filledForm) }}" method="POST" class="inline"
              onsubmit="return confirm('Weet je het zeker?');">
            @csrf
            @method('DELETE')
            <x-primary-button type="submit">Verwijder</x-primary-button>
        </form>
    </div>
@endsection
