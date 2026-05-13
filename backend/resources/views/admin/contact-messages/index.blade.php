@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="overflow-hidden rounded bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-3">Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody class="divide-y">
            @foreach($items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->first_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->is_read ? 'Read' : 'Unread' }}</td>
                    <td class="py-3">
                        <a class="rounded border px-3 py-1" href="{{ route('admin.contact-messages.show', $item) }}">Open</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
