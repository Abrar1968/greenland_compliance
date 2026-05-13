@props(['name', 'label' => null, 'options' => [], 'value' => null])

<label class="block">
    <span class="text-sm font-medium text-gray-700">{{ $label ?? str($name)->headline() }}</span>
    <select name="{{ $name }}" {{ $attributes->merge(['class' => 'mt-1 w-full rounded border px-3 py-2']) }}>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</label>
