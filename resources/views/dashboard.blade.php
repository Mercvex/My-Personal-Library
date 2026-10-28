@extends('layouts.library')

@section('title', 'Dashboard')
@section('content')
    <section class="page-heading">
        <h1>Hi, {{ auth()->user()->name }}</h1>
        <p>Here's a quick look at your reading collection.</p>
    </section>

    <section class="dashboard-stats">
        <article class="panel stat-card">
            <p class="stat-label">Total Books</p>
            <h2 class="stat-number">{{ $totalBooks }}</h2>
            <p class="muted">Books in your collection</p>
        </article>

        <article class="panel stat-card">
            <p class="stat-label">Currently Reading</p>
            <h2 class="stat-number">{{ $currentlyReading }}</h2>
            <p class="muted">Books you're reading now</p>
        </article>

        <article class="panel stat-card">
            <p class="stat-label">Finished Reading</p>
            <h2 class="stat-number">{{ $finishedReading }}</h2>
            <p class="muted">Books you've completed</p>
        </article>
    </section>

    <section class="dashboard-columns">
        <div class="panel">
            <div class="section-heading">
            <h2>Recently Added</h2>
            <a href="{{ route('books.index') }}">View all</a>
            </div>

            @forelse ($recentBooks as $book)
                <a href="{{ route('books.show', $book) }}" class="recent-book">
                    @if ($book->cover_image)
                        <img
                            src="{{ asset('storage/' . $book->cover_image) }}"
                            alt="Cover of {{ $book->title }}"
                            class="recent-book-cover"
                        >
                    @else
                        <div class="recent-book-cover recent-book-placeholder">
                            No Cover
                        </div>
                    @endif

                    <div class="recent-book-info">
                        <h3>{{ $book->title }}</h3>
                        <p>{{ $book->author }}</p>
                        <span class="book-status">{{ $book->status }}</span>
                    </div>
                </a>
            @empty
                <p class="muted empty-message">
                    You haven't added any books yet.
                </p>

                <a href="{{ route('books.create') }}" class="primary-btn">
                    Add Your First Book
                </a>
            @endforelse
        </div>

        <div class="panel">
            <div class="section-heading">
                <h2>Quick Actions</h2>
            </div>

            <div class="quick-actions">
                <a href="{{ route('books.create') }}" class="quick-action">
                    <span class="quick-action-symbol">＋</span>
                    <span>
                        <strong>Add New Book</strong>
                        <small>Add a book to your collection</small>
                    </span>
                </a>

                <a href="{{ route('books.index') }}" class="quick-action">
                    <span class="quick-action-symbol">▤</span>
                    <span>
                        <strong>View All Books</strong>
                        <small>Browse your collection</small>
                    </span>
                </a>

                <a href="{{ route('books.index') }}" class="quick-action">
                    <span class="quick-action-symbol">⌕</span>
                    <span>
                        <strong>Search Books</strong>
                        <small>Find a title or author</small>
                    </span>
                </a>

                <a href="{{ route('books.index', ['status' => 'Currently Reading']) }}"
                   class="quick-action">
                    <span class="quick-action-symbol">☷</span>
                    <span>
                        <strong>Currently Reading</strong>
                        <small>See books you're reading</small>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section class="dashboard-columns">
        <div class="panel">
            <div class="section-heading">
                <h2>Reading Progress</h2>
            </div>

            <p class="muted">Completed books in your collection</p>

            <div class="progress-track">
                <div
                    class="progress-fill"
                    style="width: {{ $completionProgress }}%"
                ></div>
            </div>

            <div class="progress-details">
                <strong>{{ $completionProgress }}%</strong>
                <span class="muted">
                    {{ $finishedReading }} of {{ $totalBooks }} books finished
                </span>
            </div>

            @if ($avgReadingProgress !== null)
                <p class="muted" style="margin-top: 14px; font-size: 13px;">
                    Average page progress across tracked books:
                    <strong>{{ $avgReadingProgress }}%</strong>
                </p>
            @endif
        </div>

        <div class="panel">
            <div class="section-heading">
                <h2>My Favorites</h2>
                <a href="{{ route('favorites.index') }}">View all</a>
            </div>

            @forelse ($favoriteBooks as $book)
                <a href="{{ route('books.show', $book) }}" class="favorite-book">
                    @if ($book->cover_image)
                        <img
                            src="{{ asset('storage/' . $book->cover_image) }}"
                            alt="Cover of {{ $book->title }}"
                            class="favorite-book-cover"
                        >
                    @else
                        <div class="favorite-book-cover recent-book-placeholder">
                            No Cover
                        </div>
                    @endif

                    <div>
                        <h3>{{ $book->title }}</h3>
                        <p class="muted">{{ $book->author }}</p>
                    </div>
                </a>
            @empty
                <p class="muted empty-message">
                    Your reading list is empty. Add a book to your favorites
                    to see it here.
                </p>
            @endforelse
        </div>
    </section>
@endsection
