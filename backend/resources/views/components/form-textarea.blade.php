@props(['name', 'label' => null, 'value' => null, 'rows' => 5])

<label class="block">
    <span class="text-sm font-medium text-gray-700">{{ $label ?? str($name)->headline() }}</span>
    <textarea
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => 'mt-1 w-full rounded border px-3 py-2']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</label>
