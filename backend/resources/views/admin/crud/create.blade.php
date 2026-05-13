@extends('layouts.admin')

@section('title', 'Create '.$resource['title'])

@section('content')
<form method="POST" action="{{ route('admin.'.$resource['route'].'.store') }}" enctype="multipart/form-data" class="rounded bg-white p-6 shadow">
    @csrf
    @include('admin.crud.form')
    <button class="rounded bg-primary px-4 py-2 font-semibold text-white">Save</button>
</form>
@endsection
