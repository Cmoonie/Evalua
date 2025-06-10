@extends('layouts.app')

@php $header = 'Beoordeling bekijken'; @endphp

@section('content')
    <div class="flex flex-wrap justify-between">
        <h1 class="text-2xl text-primary font-bold mb-4">
            Beoordelingsformulier {{ $filledForm->form->title }}
        </h1>

        <a href="{{ route('filled_forms.pdf', $filledForm) }}" target="_blank">
            <x-secondary-button>Download PDF</x-secondary-button>
        </a>
    </div>

        <div class="flex flex-col lg:flex-row justify-between gap-6 mb-4">
                <!-- Tabel 1: Basisinformatie formulier -->
                    <table class="w-full lg:w-1/2 bg-white border border-gray-200 shadow-sm">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left text-primary" colspan="2">Basisinformatie formulier</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Vak</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->subject }}</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">OE-code</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->oe_code }}</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Beschrijving</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->form->description }}</td>
                        </tr>
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600">Datum ingevuld</td>
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @if($filledForm->created_at->ne($filledForm->updated_at))
                            <tr class="border-t">
                                <td class="px-4 py-2 font-medium text-gray-600">Datum aangepast</td>
                                <td class="px-4 py-2 text-gray-800">{{ $filledForm->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endif
                        <tr class="border-t">
                            <td colspan="2" class="px-4 py-2 text-right space-x-2">
                                <x-secondary-button>
                                    <a href="{{ route('filled_forms.edit', $filledForm) }}">Bewerk</a>
                                </x-secondary-button>
                                <form action="{{ route('filled_forms.destroy', $filledForm) }}" method="POST" class="inline" onsubmit="return confirm('Weet je het zeker?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-secondary-button type="submit">Verwijder</x-secondary-button>
                                </form>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                <table class="w-full lg:w-1/2 border border-gray-200 shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-primary">
                            <th class="px-4 py-2 text-left">Eindbeoordeling</th>
                            <th class="px-4 py-2 text-center">Totaal punten</th>
                            <th class="px-4 py-2 text-center">Behaald</th>
                            <th class="px-4 py-2 text-center">Minimale punten</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($competencies as $comp)
                        <tr class="border-b {{ $comp['stateClass'] }}">
                            <td class="px-4 py-2">{{ $comp['name'] }}</td>
                            <td class="px-4 py-2 text-center"> 25 </td>
                            <td class="px-4 py-2 text-center"> {{ $comp['total'] }} </td>
                            <td class="px-4 py-2 text-center">12</td>
                            <td class="px-4 py-2 text-center"> {{ $comp['statusText'] }} </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 font-semibold text-primary">
                        <td class="px-4 py-2 text-start">Cijfer: {{ $finalGrade }}</td>
                        <td class="px-4 py-2 text-center"> 150 </td>
                        <td class="px-4 py-2 text-center"> {{ $grandTotal }} </td>
                        <td class="px-4 py-2 text-center">72</td>
                        <td class="px-4 py-2 text-center"> {{ $finalStatus }} </td>
                    </tr>
                    <tr class="bg-white text-xs text-primary">
                        <td colspan="5">Toelichting:
                        <11 = Onvoldoende || 12 - 16 = Voldoende || 17 - 25 = Goed.

                        Voldoende alleen mogelijk mits alle activiteiten en competenties behaald zijn met een voldoende.
                        </td>
                    </tr>
                    </tbody>
                </table>
        </div>

    <!-- Tabel 2: Studentinformatie -->
    <div class="flex flex-row justify-between gap-6 mb-4">
        <table class="w-1/2 bg-white border border-gray-200 text-left shadow-sm">
            <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 font-semibold text-left text-primary" colspan="2">Studentinformatie</th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-t">
                <td class="px-4 w-1/2 py-2 font-medium text-gray-600">Studentnaam</td>
                <td class="px-4 py-2 text-gray-800">{{ $filledForm->student_name }}</td>
            </tr>
            <tr class="border-t">
                <td class="px-4 w-1/2 py-2 font-medium text-gray-600">Studentnummer</td>
                <td class="px-4 py-2 text-gray-800">{{ $filledForm->student_number }}</td>
            </tr>
            <tr class="border-t">
                <td class="px-4 w-1/2 py-2 font-medium text-gray-600">Titel opdracht</td>
                <td class="px-4 py-2 text-gray-800">{{ $filledForm->assignment }}</td>
            </tr>
            </tbody>
        </table>

        <!-- Tabel 3: Bedrijfsinformatie -->
        <table class="w-1/2 bg-white border border-gray-200 text-left shadow-sm">
            <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left font-semibold text-primary" colspan="2">Bedrijfsinformatie</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($filledForm->business_name))
                <tr class="border-t">
                    <td class="px-4 py-2 w-1/2 font-medium text-gray-600">Bedrijfsnaam</td>
                    <td class="px-4 py-2 text-gray-800">{{ $filledForm->business_name }}</td>
                </tr>
            @endif
            @if(!empty($filledForm->business_location))
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Bedrijfslocatie</td>
                    <td class="px-4 py-2 text-gray-800">{{ $filledForm->business_location }}</td>
                </tr>
            @endif
            @if(!empty($filledForm->start_date))
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Startdatum</td>
                    <td class="px-4 py-2 text-gray-800">{{ $filledForm->start_date->format('Y-m-d') }}</td>
                </tr>
            @endif
            @if(!empty($filledForm->end_date))
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Einddatum</td>
                    <td class="px-4 py-2 text-gray-800">{{ $filledForm->end_date->format('Y-m-d') }}</td>
                </tr>
            @else
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600" colspan="2">Niet ingevuld</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div class="flex flex-row justify-between gap-6 mb-8">
            <table class="w-1/2 bg-white border border-gray-200 shadow-sm">
                <thead>
                <tr class="bg-gray-100">
                    <th class="w-1/2 px-4 py-2 text-left font-semibold text-primary">Rol</th>
                    <th class="px-4 py-2 text-left font-semibold text-primary">Naam</th>
                </tr>
                </thead>
                <tbody>
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium text-gray-600">Eerste examinator</td>
                    <td class="px-4 py-2 text-gray-800">{{ $filledForm->user->name }}</td>
                </tr>

                                        @if($filledForm->examinator)
                                            <tr class="border-t">
                                                <td class="px-4 py-2 font-medium text-gray-600">Tweede examinator</td>
                                                <td class="px-4 py-2 text-gray-800">{{ $filledForm->examinator }}</td>
                                            </tr>
                                        @endif

                </tbody>
            </table>
                <table class="w-1/2 bg-white border border-gray-200 shadow-sm">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left font-semibold text-primary" colspan="2">Algemene opmerkingen</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    @if(!empty($filledForm->comment))
                        <tr class="border-t">
                            <td class="px-4 py-2 text-gray-800">{{ $filledForm->comment }}</td>
                        </tr>
                    @else
                        <tr class="border-t">
                            <td class="px-4 py-2 font-medium text-gray-600" colspan="2">Niet ingevuld</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
    </div>


        @foreach ($competencies as $comp)
            <div class="p-4 border border-gray-200 bg-white rounded-lg mb-4">
                <h2 class="text-xl font-bold text-primary mb-2">Competentie: {{ $comp['name'] }}</h2>
                        <table class="w-full table-auto border-collapse mb-2">
                            <thead>
                            <tr class="bg-gray-200 text-primary">
                                <th class="w-1/6 p-2 text-left">Component</th>
                                <th class="w-1/6 p-2 text-center">Onvoldoende (0)</th>
                                <th class="w-1/6 p-2 text-center">Voldoende (3)</th>
                                <th class="w-1/6 p-2 text-center">Goed (5)</th>
                                <th class="w-1/12 p-2 text-center">Punten</th>
                                <th class="w-1/6 p-2">Opmerking</th>
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
                                        <td class="p-2 text-center break-words whitespace-normal">{{ $c['comment'] }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 text-primary font-bold">
                                    <td colspan="4" class="p-2 text-right">Totaal</td>
                                    <td class="p-2 text-center">{{ $comp['total'] }}</td>
                                    <td class="p-2 text-center">{{ $comp['statusText'] }}</td>
                                </tr>
                            </tbody>
                        </table>

                    <div class="grid lg:grid-cols-3 grid-cols-1  gap-6">
                        <x-info-card :title="'Knock-out Criteria & Deliverables'">
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
        @endforeach

        <a href="{{ route('forms.index') }}"><x-primary-button>
                Terug naar lijst
            </x-primary-button>
        </a>
@endsection
