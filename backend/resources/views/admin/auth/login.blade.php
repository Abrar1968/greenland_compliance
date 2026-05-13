@extends('layouts.admin')

@section('title', 'Admin Login')

@section('content')
<div class="mx-auto mt-20 max-w-md rounded bg-white p-8 shadow">
    <h1 class="mb-6 text-2xl font-bold">Admin Login</h1>
    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
        @csrf
        <label class="block">
            <span class="text-sm font-medium">Email</span>
            <input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded border px-3 py-2" required>
        </label>
        <label class="block">
            <span class="text-sm font-medium">Password</span>
            <input name="password" type="password" class="mt-1 w-full rounded border px-3 py-2" required>
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1"> Remember me
        </label>
        <button class="w-full rounded bg-primary px-4 py-2 font-semibold text-white">Login</button>
    </form>
</div>
@endsection
