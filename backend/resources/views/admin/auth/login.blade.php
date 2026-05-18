@extends('layouts.admin')

@section('title', 'Admin Login')

@section('content')
<div class="admin-auth-card p-8">
    <div class="mb-7 flex items-center gap-4">
        <span class="admin-brand-mark">G</span>
        <div>
            <h1 class="text-2xl font-extrabold">Greenland Admin</h1>
            <p class="mt-1 text-sm text-gray-500">Sign in to manage website content.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
        @csrf
        <label class="admin-field">
            <span class="admin-label">Email</span>
            <input name="email" type="email" value="{{ old('email') }}" class="admin-input" required>
        </label>
        <label class="admin-field">
            <span class="admin-label">Password</span>
            <input name="password" type="password" class="admin-input" required>
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" value="1"> Remember me
        </label>
        <button class="admin-btn admin-btn-primary w-full">Login</button>
    </form>
</div>
@endsection
