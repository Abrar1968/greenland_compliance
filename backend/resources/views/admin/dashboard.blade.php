@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid gap-4 md:grid-cols-3">
    <div class="rounded bg-white p-6 shadow">
        <div class="text-sm text-gray-500">Unread Messages</div>
        <div class="mt-2 text-3xl font-bold">{{ $unreadMessages }}</div>
    </div>
    <div class="rounded bg-white p-6 shadow md:col-span-2">
        <div class="text-sm text-gray-500">Managed Resources</div>
        <div class="mt-2 text-3xl font-bold">{{ count($resources) }}</div>
    </div>
</div>
<div class="mt-8 grid gap-3 md:grid-cols-3">
    @foreach($resources as $resource)
        <a href="{{ route('admin.'.$resource['route'].'.index') }}" class="rounded bg-white p-4 shadow hover:ring-2 hover:ring-primary">
            <div class="font-semibold">{{ $resource['title'] }}</div>
            <div class="text-sm text-gray-500">Manage records</div>
        </a>
    @endforeach
</div>
@endsection
