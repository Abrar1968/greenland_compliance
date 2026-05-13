@extends('layouts.admin')

@section('title', 'Hero Settings')

@section('content')
<form method="POST" action="{{ route('admin.hero-settings.update') }}" class="rounded bg-white p-6 shadow">
    @csrf
    @method('PUT')
    <div class="grid gap-4 md:grid-cols-2">
        @foreach(['headline_line1','headline_line2','paragraph','cta1_label','cta1_href','cta2_label','cta2_href'] as $field)
            <label class="block {{ $field === 'paragraph' ? 'md:col-span-2' : '' }}">
                <span class="text-sm font-medium">{{ str($field)->headline() }}</span>
                @if($field === 'paragraph')
                    <textarea name="{{ $field }}" rows="5" class="mt-1 w-full rounded border px-3 py-2">{{ old($field, $settings->{$field}) }}</textarea>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="mt-1 w-full rounded border px-3 py-2">
                @endif
            </label>
        @endforeach
    </div>
    <button class="mt-6 rounded bg-primary px-4 py-2 font-semibold text-white">Update</button>
</form>
@endsection
