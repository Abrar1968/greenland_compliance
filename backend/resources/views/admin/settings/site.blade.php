@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Site</div>
        <h1 class="admin-page-title">Site Settings</h1>
        <p class="admin-page-copy">Manage the website identity, contact details, footer content, map, and core media assets.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('PUT')

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-section-title"><span class="admin-icon-box">B</span>Basic Information</h2>
                <p class="admin-section-copy">Primary metadata used by the frontend.</p>
            </div>
        </div>
        <div class="admin-card-pad admin-form-grid">
            @foreach(['site_name','meta_title'] as $field)
                <label class="admin-field">
                    <span class="admin-label">{{ str($field)->headline() }}</span>
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="admin-input">
                </label>
            @endforeach
            <label class="admin-field admin-field-wide">
                <span class="admin-label">Meta Description</span>
                <textarea name="meta_description" rows="4" class="admin-textarea">{{ old('meta_description', $settings->meta_description) }}</textarea>
            </label>
        </div>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-section-title"><span class="admin-icon-box">C</span>Contact Information</h2>
                <p class="admin-section-copy">Phone, email, address, office hours, and map embed.</p>
            </div>
        </div>
        <div class="admin-card-pad admin-form-grid">
            @foreach(['primary_phone','primary_email','business_hours'] as $field)
                <label class="admin-field">
                    <span class="admin-label">{{ str($field)->headline() }}</span>
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="admin-input">
                </label>
            @endforeach
            <label class="admin-field admin-field-wide">
                <span class="admin-label">Address</span>
                <textarea name="address" rows="4" class="admin-textarea">{{ old('address', $settings->address) }}</textarea>
            </label>
            <label class="admin-field admin-field-wide">
                <span class="admin-label">Map Embed URL</span>
                <textarea name="map_embed_url" rows="3" class="admin-textarea">{{ old('map_embed_url', $settings->map_embed_url) }}</textarea>
            </label>
        </div>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-section-title"><span class="admin-icon-box">F</span>Footer and CTA</h2>
                <p class="admin-section-copy">Footer description, call-to-action text, and copyright copy.</p>
            </div>
        </div>
        <div class="admin-card-pad admin-form-grid">
            @foreach(['footer_cta_title','footer_cta_button_label','footer_cta_button_href','copyright_text','how_we_work_video_url'] as $field)
                <label class="admin-field">
                    <span class="admin-label">{{ str($field)->headline() }}</span>
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="admin-input">
                </label>
            @endforeach
            @foreach(['footer_description','footer_cta_text'] as $field)
                <label class="admin-field admin-field-wide">
                    <span class="admin-label">{{ str($field)->headline() }}</span>
                    <textarea name="{{ $field }}" rows="4" class="admin-textarea">{{ old($field, $settings->{$field}) }}</textarea>
                </label>
            @endforeach
        </div>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-section-title"><span class="admin-icon-box">M</span>Media and Downloads</h2>
                <p class="admin-section-copy">Upload replacement assets for the logo, office image, and company presentation.</p>
            </div>
        </div>
        <div class="admin-card-pad admin-form-grid">
            @foreach(['logo_path' => 'Logo', 'office_image_path' => 'Office Image'] as $field => $label)
                <label class="admin-field">
                    <span class="admin-label">{{ $label }}</span>
                    <input type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp" class="admin-file">
                    @if($settings->{$field})
                        <span class="admin-file-note">Current: {{ $settings->{$field} }}</span>
                    @endif
                </label>
            @endforeach
            <label class="admin-field">
                <span class="admin-label">Company Presentation PDF</span>
                <input type="file" name="company_presentation_file" accept="application/pdf" class="admin-file">
                @if($settings->company_presentation_file)
                    <span class="admin-file-note">Current: {{ $settings->company_presentation_file }}</span>
                @endif
            </label>
        </div>
    </section>

    <div class="flex justify-end">
        <button class="admin-btn admin-btn-primary">Save Changes</button>
    </div>
</form>
@endsection
