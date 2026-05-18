<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Greenland Compliance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="@auth('admin') min-h-screen text-gray-800 @else admin-auth-body @endauth">
    @auth('admin')
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                    <span class="admin-brand-mark">G</span>
                    <span>
                        <span class="admin-brand-title">Greenland Admin</span>
                        <span class="admin-brand-subtitle block">Business & Compliance</span>
                    </span>
                </a>

                @include('admin.partials.sidebar-nav')

                <div class="admin-sidebar-footer">
                    <a href="{{ rtrim(config('app.frontend_url'), '/') }}" target="_blank" class="admin-btn admin-btn-muted w-full border-white/15 bg-transparent text-white hover:bg-white/5">
                        View Website
                        <span aria-hidden="true">-></span>
                    </a>
                </div>
            </aside>

            <main class="admin-main">
                <header class="admin-topbar">
                    <div class="admin-topbar-title">
                        <span class="admin-menu-button" aria-hidden="true">
                            <span class="text-lg leading-none">|||</span>
                        </span>
                        <span>@yield('title', 'Admin')</span>
                    </div>

                    <div class="admin-topbar-actions">
                        <div class="admin-search" aria-label="Search placeholder">
                            <span class="font-bold" aria-hidden="true">S</span>
                            <span>Search content</span>
                        </div>
                        <span class="admin-menu-button" title="Unread messages">
                            {{ \App\Models\ContactMessage::where('is_read', false)->count() }}
                        </span>
                        <span class="admin-avatar inline-flex items-center justify-center">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </span>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="admin-btn admin-btn-muted border-white/15 bg-white/5 text-white hover:bg-white/10">Logout</button>
                        </form>
                    </div>
                </header>

                <section class="admin-content">
                    @if(session('status'))
                        <div class="admin-alert admin-alert-success">{{ session('status') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="admin-alert admin-alert-error">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </section>
            </main>
        </div>
    @else
        <main class="flex min-h-screen items-center justify-center px-5 py-10">
            <div class="w-full max-w-md">
                @if(session('status'))
                    <div class="admin-alert admin-alert-success">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="admin-alert admin-alert-error">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    @endauth
</body>
</html>
