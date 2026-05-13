<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - Greenland Compliance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        @auth('admin')
            <aside class="w-72 shrink-0 bg-secondary text-white">
                <div class="border-b border-white/10 p-5">
                    <div class="font-bold">Greenland Admin</div>
                    <div class="text-xs text-white/60">{{ auth('admin')->user()->email }}</div>
                </div>
                @include('admin.partials.sidebar-nav')
            </aside>
        @endauth
        <main class="flex-1">
            @auth('admin')
                <header class="flex items-center justify-between border-b bg-white px-8 py-4">
                    <h1 class="text-xl font-semibold">@yield('title', 'Admin')</h1>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Logout</button>
                    </form>
                </header>
            @endauth
            <section class="p-8">
                @if(session('status'))
                    <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('status') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-700">
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
</body>
</html>
