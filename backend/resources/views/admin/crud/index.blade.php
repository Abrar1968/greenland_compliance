@extends('layouts.admin')

@section('title', $resource['title'])

@section('content')
<div class="mb-4 flex items-center justify-between">
    <h2 class="text-lg font-semibold">{{ $resource['title'] }}</h2>
    <a href="{{ route('admin.'.$resource['route'].'.create') }}" class="rounded bg-primary px-4 py-2 text-sm font-semibold text-white">Create</a>
</div>
<div class="overflow-hidden rounded bg-white shadow">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3">ID</th>
                @foreach(array_slice($resource['fields'], 0, 4) as $field)
                    <th class="px-4 py-3">{{ str($field)->headline() }}</th>
                @endforeach
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->id }}</td>
                    @foreach(array_slice($resource['fields'], 0, 4) as $field)
                        <td class="px-4 py-3">{{ str($item->{$field} ?? '')->limit(60) }}</td>
                    @endforeach
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a class="rounded border px-3 py-1" href="{{ route('admin.'.$resource['route'].'.edit', $item) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.'.$resource['route'].'.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded border border-red-300 px-3 py-1 text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
