@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Overview</div>
        <h1 class="admin-page-title">Welcome back, Admin</h1>
        <p class="admin-page-copy">Manage Greenland Business & Compliance content, contact messages, and frontend data from one place.</p>
    </div>
    <a href="{{ route('admin.site-settings.edit') }}" class="admin-btn admin-btn-primary">Quick Actions</a>
</div>

<div class="admin-stat-grid mb-6">
    <div class="admin-stat-card">
        <div class="flex items-center gap-3">
            <span class="admin-icon-box">M</span>
            <div>
                <div class="admin-muted-label">Unread Messages</div>
                <div class="admin-big-number">{{ $unreadMessages }}</div>
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">{{ $unreadMessages ? 'Needs attention' : 'No new messages' }}</p>
    </div>
    <div class="admin-stat-card">
        <div class="flex items-center gap-3">
            <span class="admin-icon-box">R</span>
            <div>
                <div class="admin-muted-label">Managed Resources</div>
                <div class="admin-big-number">{{ count($resources) }}</div>
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">Content modules</p>
    </div>
    <div class="admin-stat-card">
        <div class="flex items-center gap-3">
            <span class="admin-icon-box">U</span>
            <div>
                <div class="admin-muted-label">Last Updated</div>
                <div class="admin-big-number text-lg">{{ now()->format('M j, Y') }}</div>
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">Site content</p>
    </div>
    <div class="admin-stat-card">
        <div class="flex items-center gap-3">
            <span class="admin-icon-box">S</span>
            <div>
                <div class="admin-muted-label">Site Status</div>
                <div class="admin-big-number text-lg text-emerald-700">Published</div>
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">Everything is live</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-section-title">Content Management</h2>
            <p class="admin-section-copy">Update all website content and frontend API data.</p>
        </div>
        <a href="{{ route('admin.contact-messages.index') }}" class="admin-btn admin-btn-muted">View Messages</a>
    </div>

    <div class="admin-card-pad">
        <div class="admin-resource-grid">
            @foreach($resources as $resource)
                @php
                    $count = isset($resource['model']) ? $resource['model']::count() : null;
                    $initial = strtoupper(substr($resource['title'], 0, 1));
                @endphp
                <a href="{{ route('admin.'.$resource['route'].'.index') }}" class="admin-resource-card">
                    <div class="flex items-start gap-3">
                        <span class="admin-icon-box">{{ $initial }}</span>
                        <span>
                            <span class="block font-bold text-gray-900">{{ $resource['title'] }}</span>
                            <span class="mt-1 block text-sm text-gray-500">Manage {{ str($resource['title'])->lower() }}</span>
                            @if(! is_null($count))
                                <span class="mt-3 block text-sm font-semibold text-gray-600">{{ $count }} items</span>
                            @endif
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
