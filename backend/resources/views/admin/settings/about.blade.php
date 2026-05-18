@extends('layouts.admin')

@section('title', 'About Settings')

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">About</div>
        <h1 class="admin-page-title">About Settings</h1>
        <p class="admin-page-copy">Update the About page hero, overview, mission, approach, and footer call-to-action content.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.about-settings.update') }}" enctype="multipart/form-data" class="admin-card admin-card-pad">
    @csrf
    @method('PUT')
    <div class="admin-form-grid">
        @foreach(['banner_label','hero_heading_line1','hero_heading_line2','hero_paragraph','hero_cta_label','hero_cta_href','overview_paragraph1','overview_paragraph2','overview_callout','mission_heading','mission_intro','approach_intro1','approach_intro2','footer_cta_text','footer_cta_button_label','footer_cta_button_href'] as $field)
            <label class="admin-field {{ str_contains($field, 'paragraph') || str_contains($field, 'intro') || str_contains($field, 'callout') || str_contains($field, 'text') ? 'admin-field-wide' : '' }}">
                <span class="admin-label">{{ str($field)->headline() }}</span>
                @if(str_contains($field, 'paragraph') || str_contains($field, 'intro') || str_contains($field, 'callout') || str_contains($field, 'text'))
                    <textarea name="{{ $field }}" rows="4" class="admin-textarea">{{ old($field, $settings->{$field}) }}</textarea>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="admin-input">
                @endif
            </label>
        @endforeach
        <label class="admin-field">
            <span class="admin-label">Hero Image</span>
            <input type="file" name="hero_image_path" accept="image/jpeg,image/png,image/webp" class="admin-file">
            @if($settings->hero_image_path)<span class="admin-file-note">Current: {{ $settings->hero_image_path }}</span>@endif
        </label>
    </div>
    <div class="mt-6 flex justify-end">
        <button class="admin-btn admin-btn-primary">Update</button>
    </div>
</form>
@endsection
