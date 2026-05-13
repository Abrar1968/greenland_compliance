@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="rounded bg-white p-6 shadow">
    @csrf
    @method('PUT')
    <div class="grid gap-4 md:grid-cols-2">
        @foreach(['site_name','meta_title','meta_description','primary_phone','primary_email','address','business_hours','footer_description','footer_cta_title','footer_cta_text','footer_cta_button_label','footer_cta_button_href','map_embed_url','copyright_text','how_we_work_video_url'] as $field)
            <label class="block {{ str_contains($field, 'description') || str_contains($field, 'text') || $field === 'address' || $field === 'map_embed_url' ? 'md:col-span-2' : '' }}">
                <span class="text-sm font-medium">{{ str($field)->headline() }}</span>
                @if(str_contains($field, 'description') || str_contains($field, 'text') || $field === 'address' || $field === 'map_embed_url')
                    <textarea name="{{ $field }}" rows="4" class="mt-1 w-full rounded border px-3 py-2">{{ old($field, $settings->{$field}) }}</textarea>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="mt-1 w-full rounded border px-3 py-2">
                @endif
            </label>
        @endforeach
        @foreach(['logo_path' => 'Logo', 'office_image_path' => 'Office Image'] as $field => $label)
            <label class="block">
                <span class="text-sm font-medium">{{ $label }}</span>
                <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp" class="mt-1 w-full rounded border px-3 py-2">
                @if($settings->{$field})<span class="text-xs text-gray-500">Current: {{ $settings->{$field} }}</span>@endif
            </label>
        @endforeach
        <label class="block">
            <span class="text-sm font-medium">Company Presentation PDF</span>
            <input type="file" name="company_presentation_file" accept="application/pdf" class="mt-1 w-full rounded border px-3 py-2">
            @if($settings->company_presentation_file)<span class="text-xs text-gray-500">Current: {{ $settings->company_presentation_file }}</span>@endif
        </label>
    </div>
    <button class="mt-6 rounded bg-primary px-4 py-2 font-semibold text-white">Update</button>
</form>
@endsection
