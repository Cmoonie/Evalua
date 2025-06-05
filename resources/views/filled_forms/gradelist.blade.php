@extends('layouts.app')

@php
    $header = 'Cijferlijsten';
@endphp

@section('content')
    <div class="container mx-auto p-6">
        <div class="p-4 border border-gray-200 bg-white rounded-lg">
            <h1 class="text-2xl text-primary font-bold mb-4">Overzicht Cijfers Per Vak</h1>
            @if($forms->isEmpty())
                <p class="text-primary">Geen formulieren gevonden. Tijd om er een te maken!</p>
            @else

                    @foreach($forms as $form)

                        <div class="mb-8" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                class="bg-primary py-2 px-4 text-2xl font-bold text-white shadow-lg hover:bg-secondary dark:hover:bg-darktext mb-4 flex items-center justify-between w-full rounded-lg transition-colors duration-300">
                                <span>{{ $form->subject }}</span>
                                <svg :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 transition-transform duration-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open">

                    <table class="w-full bg-white shadow rounded">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-left">Studentnaam</th>
                            <th class="p-3 text-left">Cijfer</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Datum ingevuld</th>
                            <th class="p-3 text-left">Datum aangepast</th>
                            <th class="p-3 text-left">Acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($form->filledForms as $filledForm)
                            <tr class="border-t">
                                <td class="p-3">
                                    {{ $filledForm->student_name }}
                                </td>
                                <td class="p-3">
                                    {{ $filledForm->finalGrade ?? '–' }}
                                </td>
                                <td class="p-3">
                                    {{ $filledForm->finalStatus ?? '–' }}
                                </td>
                                <td class="p-3">
                                    {{ $form->created_at->format('d-m-Y') }}
                                </td>
                                <td class="p-3">
                                    @if($filledForm->created_at->ne($filledForm->updated_at))
                                        {{ $filledForm->updated_at->format('Y-m-d H:i') }}
                                    @else
                                        Niet aangepast
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('filled_forms.show', $filledForm) }}">
                                        <x-primary-button>
                                            Meer informatie
                                        </x-primary-button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                            </div>
                            </div>
                @endforeach

            @endif
        </div>
    </div>
@endsection

