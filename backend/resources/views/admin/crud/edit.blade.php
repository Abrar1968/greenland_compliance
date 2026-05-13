@extends('layouts.admin')

@section('title', 'Edit '.$resource['title'])

@section('content')
<form method="POST" action="{{ route('admin.'.$resource['route'].'.update', $item) }}" enctype="multipart/form-data" class="rounded bg-white p-6 shadow">
    @csrf
    @method('PUT')
    @include('admin.crud.form')
    <button class="rounded bg-primary px-4 py-2 font-semibold text-white">Update</button>
</form>
@endsection
