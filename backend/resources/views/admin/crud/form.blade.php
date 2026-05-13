<div class="grid gap-4 md:grid-cols-2">
@foreach($resource['fields'] as $field)
    <label class="block {{ in_array($field, ['description','body','content','quote','answer','question'], true) ? 'md:col-span-2' : '' }}">
        <span class="text-sm font-medium">{{ str($field)->headline() }}</span>
        @if(($resource['selects'][$field] ?? null) && isset($options[$field]))
            <select name="{{ $field }}" class="mt-1 w-full rounded border px-3 py-2">
                @foreach($options[$field] as $id => $label)
                    <option value="{{ $id }}" @selected(old($field, $item->{$field}) == $id)>{{ $label }}</option>
                @endforeach
            </select>
        @elseif($field === 'is_active')
            <label class="mt-2 flex items-center gap-2"><input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $item->{$field} ?? true))> Active</label>
        @elseif(in_array($field, ['description','body','content','quote','answer','question'], true))
            <textarea name="{{ $field }}" rows="6" class="mt-1 w-full rounded border px-3 py-2">{{ old($field, $item->{$field}) }}</textarea>
        @else
            <input name="{{ $field }}" value="{{ old($field, $item->{$field}) }}" class="mt-1 w-full rounded border px-3 py-2">
        @endif
    </label>
@endforeach
@foreach(($resource['images'] ?? []) as $field => $directory)
    <label class="block">
        <span class="text-sm font-medium">{{ str($field)->headline() }}</span>
        <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded border px-3 py-2">
        @if($item->{$field})
            <span class="mt-1 block text-xs text-gray-500">Current: {{ $item->{$field} }}</span>
        @endif
    </label>
@endforeach
@foreach(($resource['files'] ?? []) as $field => $directory)
    <label class="block">
        <span class="text-sm font-medium">{{ str($field)->headline() }}</span>
        <input type="file" name="{{ $field }}" class="mt-1 w-full rounded border px-3 py-2">
        @if($item->{$field})
            <span class="mt-1 block text-xs text-gray-500">Current: {{ $item->{$field} }}</span>
        @endif
    </label>
@endforeach
</div>
