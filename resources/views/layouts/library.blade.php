
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Personal Library')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="profile">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <div class="profile-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="profile-handle">
                        {{ '@' . \Illuminate\Support\Str::before(auth()->user()->email, '@') }}
                    </div>
                </div>
            </div>

            <nav class="nav-links">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('books.index') }}"
                   class="nav-link {{ request()->routeIs('books.index', 'books.show', 'books.edit', 'books.read') ? 'active' : '' }}">
                    My Books
                </a>

                <a href="{{ route('books.create') }}"
                   class="nav-link {{ request()->routeIs('books.create') ? 'active' : '' }}">
                    Add Book
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="nav-link {{ request()->routeIs('favorites.index') ? 'active' : '' }}">
                    ★ Favorites
                </a>
            </nav>

            <div class="logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <header class="topbar">
                <form class="global-search"
                      action="{{ route('books.index') }}"
                      method="GET">
                    <input
                        type="search"
                        name="search"
                        placeholder="Search books, authors, or categories..."
                        value="{{ request('search') }}"
                        aria-label="Search books"
                    >
                </form>
            </header>

            <div class="page-content">
                @if (session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>