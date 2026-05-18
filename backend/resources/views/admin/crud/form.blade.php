<div class="admin-form-grid">
@foreach($resource['fields'] as $field)
    @php
        $isWide = in_array($field, ['description','body','content','quote','answer','question'], true);
    @endphp
    <label class="admin-field {{ $isWide ? 'admin-field-wide' : '' }}">
        <span class="admin-label">{{ str($field)->headline() }}</span>
        @if(($resource['selects'][$field] ?? null) && isset($options[$field]))
            <select name="{{ $field }}" class="admin-select">
                @foreach($options[$field] as $id => $label)
                    <option value="{{ $id }}" @selected(old($field, $item->{$field}) == $id)>{{ $label }}</option>
                @endforeach
            </select>
        @elseif($field === 'is_active')
            <span class="inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $item->{$field} ?? true))>
                <span class="text-sm font-semibold text-gray-700">Active</span>
            </span>
        @elseif($isWide)
            <textarea name="{{ $field }}" rows="6" class="admin-textarea">{{ old($field, $item->{$field}) }}</textarea>
        @else
            <input name="{{ $field }}" value="{{ old($field, $item->{$field}) }}" class="admin-input">
        @endif
    </label>
@endforeach
@foreach(($resource['images'] ?? []) as $field => $directory)
    <label class="admin-field">
        <span class="admin-label">{{ str($field)->headline() }}</span>
        <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp" class="admin-file">
        @if($item->{$field})
            <span class="admin-file-note">Current: {{ $item->{$field} }}</span>
        @endif
    </label>
@endforeach
@foreach(($resource['files'] ?? []) as $field => $directory)
    <label class="admin-field">
        <span class="admin-label">{{ str($field)->headline() }}</span>
        <input type="file" name="{{ $field }}" class="admin-file">
        @if($item->{$field})
            <span class="admin-file-note">Current: {{ $item->{$field} }}</span>
        @endif
    </label>
@endforeach
</div>
