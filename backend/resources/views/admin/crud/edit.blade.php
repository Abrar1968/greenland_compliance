@extends('layouts.admin')

@section('title', 'Edit '.$resource['title'])

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Edit</div>
        <h1 class="admin-page-title">{{ $resource['title'] }} #{{ $item->id }}</h1>
        <p class="admin-page-copy">Update this record without changing the public frontend structure.</p>
    </div>
    <a href="{{ route('admin.'.$resource['route'].'.index') }}" class="admin-btn admin-btn-muted">Back to list</a>
</div>

<form method="POST" action="{{ route('admin.'.$resource['route'].'.update', $item) }}" enctype="multipart/form-data" class="admin-card admin-card-pad">
    @csrf
    @method('PUT')
    @include('admin.crud.form')
    <div class="mt-6 flex justify-end">
        <button class="admin-btn admin-btn-primary">Update</button>
    </div>
</form>
@endsection
