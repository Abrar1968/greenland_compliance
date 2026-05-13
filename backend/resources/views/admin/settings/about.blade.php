@extends('layouts.admin')

@section('title', 'About Settings')

@section('content')
<form method="POST" action="{{ route('admin.about-settings.update') }}" enctype="multipart/form-data" class="rounded bg-white p-6 shadow">
    @csrf
    @method('PUT')
    <div class="grid gap-4 md:grid-cols-2">
        @foreach(['banner_label','hero_heading_line1','hero_heading_line2','hero_paragraph','hero_cta_label','hero_cta_href','overview_paragraph1','overview_paragraph2','overview_callout','mission_heading','mission_intro','approach_intro1','approach_intro2','footer_cta_text','footer_cta_button_label','footer_cta_button_href'] as $field)
            <label class="block {{ str_contains($field, 'paragraph') || str_contains($field, 'intro') || str_contains($field, 'callout') || str_contains($field, 'text') ? 'md:col-span-2' : '' }}">
                <span class="text-sm font-medium">{{ str($field)->headline() }}</span>
                @if(str_contains($field, 'paragraph') || str_contains($field, 'intro') || str_contains($field, 'callout') || str_contains($field, 'text'))
                    <textarea name="{{ $field }}" rows="4" class="mt-1 w-full rounded border px-3 py-2">{{ old($field, $settings->{$field}) }}</textarea>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="mt-1 w-full rounded border px-3 py-2">
                @endif
            </label>
        @endforeach
        <label class="block">
            <span class="text-sm font-medium">Hero Image</span>
            <input type="file" name="hero_image_path" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded border px-3 py-2">
            @if($settings->hero_image_path)<span class="text-xs text-gray-500">Current: {{ $settings->hero_image_path }}</span>@endif
        </label>
    </div>
    <button class="mt-6 rounded bg-primary px-4 py-2 font-semibold text-white">Update</button>
</form>
@endsection
