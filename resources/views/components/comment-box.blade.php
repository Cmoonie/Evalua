<div class="mt-2">
    <label class="block text-sm font-medium text-gray-700">{{ $label ?? 'Opmerking' }}</label>
    <textarea name="{{ $name }}" class="w-full border rounded p-2" rows="3">{{ $value ?? '' }}</textarea>
</div>
<x-radio-score :name="'scores[' . $label . ']'" />
<x-comment-box :name="'comments[' . $label . ']'" />
