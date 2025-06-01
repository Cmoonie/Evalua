@extends('layouts.app')

@php
    $header = 'Beoordelingen per vak';
@endphp

@section('content')

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
                        <x-primary-button>
                            <a href="{{ route('filled_forms.create', $form) }}" class="px-4 py-2">Nieuwe beoordeling</a>
                        </x-primary-button>

                        @if($filledForms->isEmpty())
                            <x-info-card :title="'404'">
                                <p class="text-gray-600 mb-1">
                                    Geen beoordelingen gevonden. Tijd om er een te maken!
                                </p>
                                <a href="{{ route('filled_forms.create', $form) }}">
                                    <x-primary-button>
                                        Maak nieuwe beoordeling
                                    </x-primary-button>
                                </a>
                            </x-info-card>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($filledForms as $filledForm)
                                    <x-info-card :title="$filledForm->student_name">
                                        <p class="text-gray-600 dark:text-gray-400 mb-1">
                                            <strong>Status:</strong> {{ $filledForm->finalStatus ?? '–' }}
                                        </p>
                                        <p class="text-gray-600 dark:text-gray-400 mb-1">
                                            <strong>Datum ingevuld:</strong> {{ $filledForm->created_at->format('Y-m-d H:i') }}
                                        </p>
                                        @if($filledForm->created_at->ne($filledForm->updated_at))
                                            <p class="text-gray-600 dark:text-gray-400 mb-1">
                                                <strong>Datum aangepast:</strong> {{ $filledForm->updated_at->format('Y-m-d H:i') }}
                                            </p>
                                        @endif
                                        <a href="{{ route('filled_forms.show', $filledForm) }}">
                                            <x-primary-button>
                                                Meer informatie
                                            </x-primary-button>
                                        </a>
                                    </x-info-card>

                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
    @endif
@endsection
