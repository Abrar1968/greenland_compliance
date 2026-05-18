@extends('layouts.admin')

@section('title', 'Create '.$resource['title'])

@section('content')
<div class="admin-page-heading">
    <div>
        <div class="admin-page-kicker">Create</div>
        <h1 class="admin-page-title">{{ $resource['title'] }}</h1>
        <p class="admin-page-copy">Add a new record for the frontend API.</p>
    </div>
    <a href="{{ route('admin.'.$resource['route'].'.index') }}" class="admin-btn admin-btn-muted">Back to list</a>
</div>

<form method="POST" action="{{ route('admin.'.$resource['route'].'.store') }}" enctype="multipart/form-data" class="admin-card admin-card-pad">
    @csrf
    @include('admin.crud.form')
    <div class="mt-6 flex justify-end">
        <button class="admin-btn admin-btn-primary">Save</button>
    </div>
</form>
@endsection
