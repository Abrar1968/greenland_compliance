@props(['name', 'label' => null, 'type' => 'text', 'value' => null])

<label class="block">
    <span class="text-sm font-medium text-gray-700">{{ $label ?? str($name)->headline() }}</span>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'mt-1 w-full rounded border px-3 py-2']) }}
    >
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</label>
