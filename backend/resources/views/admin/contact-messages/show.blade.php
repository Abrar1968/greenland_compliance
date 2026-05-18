@extends('layouts.admin')

@section('title', 'Contact Message')

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Contact</div>
        <h1 class="admin-page-title">Contact Message</h1>
        <p class="admin-page-copy">Read the submitted enquiry and remove it when it is no longer needed.</p>
    </div>
    <a href="{{ route('admin.contact-messages.index') }}" class="admin-btn admin-btn-muted">Back to inbox</a>
</div>

<div class="admin-card admin-card-pad">
    <dl class="admin-form-grid">
        <div><dt class="admin-muted-label">Name</dt><dd class="mt-1 font-bold">{{ $message->first_name }}</dd></div>
        <div><dt class="admin-muted-label">Email</dt><dd class="mt-1 font-bold">{{ $message->email }}</dd></div>
        <div><dt class="admin-muted-label">Phone</dt><dd class="mt-1">{{ $message->phone }}</dd></div>
        <div><dt class="admin-muted-label">Received</dt><dd class="mt-1">{{ $message->created_at }}</dd></div>
    </dl>
    <div class="mt-6">
        <div class="admin-muted-label">Message</div>
        <p class="mt-2 whitespace-pre-line rounded-lg border border-gray-200 bg-gray-50 p-4 text-gray-800">{{ $message->message }}</p>
    </div>
    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" class="mt-6 flex justify-end" onsubmit="return confirm('Delete this message?')">
        @csrf
        @method('DELETE')
        <button class="admin-btn admin-btn-danger">Delete</button>
    </form>
</div>
@endsection
