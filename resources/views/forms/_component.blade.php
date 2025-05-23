{{--
    filled_forms/_component.blade.php

    Dit partial bevat één beoordelingselement (component) binnen een competentie.
    Het toont:
    - De componentnaam (via $label)
    - Een scorekeuze (0 / 3 / 5) via <x-radio-score>
    - Een optioneel opmerkingenveld

    Wordt herhaald gebruikt per component in create/edit voor FilledForms.
--}}

<div class="border p-4 rounded bg-white shadow-sm">
    <label class="font-semibold block mb-2">{{ $label }}</label>

    <div class="flex space-x-4 mb-2">
        @foreach ([0, 3, 5] as $score)
            <label class="inline-flex items-center">
                <input type="radio" name="scores[{{ $label }}]" value="{{ $score }}" class="mr-2">
                {{ $score }}
            </label>
        @endforeach
    </div>

    <textarea name="comments[{{ $label }}]" class="w-full border p-2 rounded" placeholder="Opmerking (optioneel)"></textarea>
</div>
