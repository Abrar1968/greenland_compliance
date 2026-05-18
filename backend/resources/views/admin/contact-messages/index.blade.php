@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Contact</div>
        <h1 class="admin-page-title">Contact Messages</h1>
        <p class="admin-page-copy">Review enquiries submitted from the public contact form.</p>
    </div>
</div>

<div class="admin-card admin-table-wrap">
    <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td class="font-bold">{{ $item->first_name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>
                        <span class="admin-badge {{ $item->is_read ? 'admin-badge-gray' : 'admin-badge-green' }}">
                            {{ $item->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </td>
                    <td>
                        <a class="admin-btn admin-btn-muted" href="{{ route('admin.contact-messages.show', $item) }}">Open</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="admin-empty">No contact messages found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
