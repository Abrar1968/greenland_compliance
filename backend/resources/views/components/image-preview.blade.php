@props(['name' => 'image', 'label' => 'Image', 'current' => null])

<div x-data="{ preview: @js($current) }">
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <template x-if="preview">
        <img :src="preview" alt="" class="mt-2 h-24 w-auto rounded border object-cover">
    </template>
    <input
        type="file"
        name="{{ $name }}"
        accept="image/jpeg,image/png,image/webp"
        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded border px-3 py-2 text-sm']) }}
    >
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
