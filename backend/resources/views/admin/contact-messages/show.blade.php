@extends('layouts.admin')

@section('title', 'Contact Message')

@section('content')
<div class="rounded bg-white p-6 shadow">
    <dl class="grid gap-4 md:grid-cols-2">
        <div><dt class="text-sm text-gray-500">Name</dt><dd class="font-medium">{{ $message->first_name }}</dd></div>
        <div><dt class="text-sm text-gray-500">Email</dt><dd class="font-medium">{{ $message->email }}</dd></div>
        <div><dt class="text-sm text-gray-500">Phone</dt><dd>{{ $message->phone }}</dd></div>
        <div><dt class="text-sm text-gray-500">Received</dt><dd>{{ $message->created_at }}</dd></div>
    </dl>
    <div class="mt-6">
        <div class="text-sm text-gray-500">Message</div>
        <p class="mt-2 whitespace-pre-line">{{ $message->message }}</p>
    </div>
    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" class="mt-6" onsubmit="return confirm('Delete this message?')">
        @csrf
        @method('DELETE')
        <button class="rounded border border-red-300 px-4 py-2 text-red-600">Delete</button>
    </form>
</div>
@endsection
