@extends('layouts.admin')

@section('title', $resource['title'])

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Content</div>
        <h1 class="admin-page-title">{{ $resource['title'] }}</h1>
        <p class="admin-page-copy">Create, edit, and remove records used by the public frontend API.</p>
    </div>
    <a href="{{ route('admin.'.$resource['route'].'.create') }}" class="admin-btn admin-btn-primary">Create {{ str($resource['title'])->singular() }}</a>
</div>

<div class="admin-card admin-table-wrap">
    <div class="admin-card-header">
        <div>
            <h2 class="admin-section-title">{{ $resource['title'] }} Records</h2>
            <p class="admin-section-copy">{{ $items->total() }} total records</p>
        </div>
    </div>

    <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    @foreach(array_slice($resource['fields'], 0, 4) as $field)
                        <th>{{ str($field)->headline() }}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="font-bold">#{{ $item->id }}</td>
                        @foreach(array_slice($resource['fields'], 0, 4) as $field)
                            <td>
                                @if($field === 'is_active')
                                    <span class="admin-badge {{ $item->{$field} ? 'admin-badge-green' : 'admin-badge-gray' }}">
                                        {{ $item->{$field} ? 'Active' : 'Inactive' }}
                                    </span>
                                @else
                                    {{ str($item->{$field} ?? '')->limit(60) }}
                                @endif
                            </td>
                        @endforeach
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <a class="admin-btn admin-btn-muted" href="{{ route('admin.'.$resource['route'].'.edit', $item) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.'.$resource['route'].'.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn admin-btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count(array_slice($resource['fields'], 0, 4)) + 2 }}" class="admin-empty">
                            No records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $items->links() }}</div>
@endsection
