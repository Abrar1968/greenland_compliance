@extends('layouts.admin')

@section('title', 'Hero Settings')

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Homepage</div>
        <h1 class="admin-page-title">Hero Settings</h1>
        <p class="admin-page-copy">Control the static hero copy and call-to-action links used on the homepage.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.hero-settings.update') }}" class="admin-card admin-card-pad">
    @csrf
    @method('PUT')
    <div class="admin-form-grid">
        @foreach(['headline_line1','headline_line2','paragraph','cta1_label','cta1_href','cta2_label','cta2_href'] as $field)
            <label class="admin-field {{ $field === 'paragraph' ? 'admin-field-wide' : '' }}">
                <span class="admin-label">{{ str($field)->headline() }}</span>
                @if($field === 'paragraph')
                    <textarea name="{{ $field }}" rows="5" class="admin-textarea">{{ old($field, $settings->{$field}) }}</textarea>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $settings->{$field}) }}" class="admin-input">
                @endif
            </label>
        @endforeach
    </div>
    <div class="mt-6 flex justify-end">
        <button class="admin-btn admin-btn-primary">Update</button>
    </div>
</form>
@endsection
