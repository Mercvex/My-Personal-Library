@extends('layouts.library')

@section('title', 'My Books')

@section('content')
    <section class="page-heading">
        <h1>My Books</h1>
        <p>Browse, search, and manage your personal collection.</p>
    </section>

    <div class="books-toolbar">
        <form action="{{ route('books.index') }}" method="GET" class="books-filter-form">
            <input
                type="search"
                name="search"
                class="form-control"
                placeholder="Search by book title or author..."
                value="{{ request('search') }}"
            >

            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="Currently Reading" {{ request('status') === 'Currently Reading' ? 'selected' : '' }}>
                    Currently Reading
                </option>
                <option value="Finished Reading" {{ request('status') === 'Finished Reading' ? 'selected' : '' }}>
                    Finished Reading
                </option>
            </select>

            <button type="submit" class="primary-btn">Search</button>
        </form>

        <a href="{{ route('books.create') }}" class="primary-btn">+ Add Book</a>
    </div>

    @if ($books->isEmpty())
        <div class="panel empty-books">
            <h2>No books found</h2>
            <p class="muted">Your books will appear here after you add them.</p>
            <a href="{{ route('books.create') }}" class="primary-btn">Add Your First Book</a>
        </div>
    @else
        <div class="books-grid">
            @foreach ($books as $book)
                @php
                    $isFav = auth()->user()->favorites()->where('book_id', $book->id)->exists();
                @endphp
                <article class="book-card">
                    @if ($book->cover_image)
                        <div class="book-cover">
                            <img
                                src="{{ asset('storage/' . $book->cover_image) }}"
                                alt="Cover of {{ $book->title }}"
                            >
                        </div>
                    @else
                        <div class="book-cover book-cover-placeholder">No Cover Available</div>
                    @endif

                    <div class="book-info">
                        <h2 class="book-title">{{ $book->title }}</h2>
                        <p class="book-author">{{ $book->author }}</p>

                        <span class="book-status">{{ $book->status }}</span>

                        @if ($book->category)
                            <p class="muted">{{ $book->category }}</p>
                        @endif

                        <div class="book-actions">
                            <a href="{{ route('books.show', $book) }}">Details</a>
                            <a href="{{ route('books.edit', $book) }}">Edit</a>

                            <form action="{{ route('books.favorite', $book) }}" method="POST">
                                @csrf
                                <button type="submit" title="{{ $isFav ? 'Remove from reading list' : 'Add to reading list' }}">
                                    {{ $isFav ? '★' : '☆' }}
                                </button>
                            </form>

                            <form
                                action="{{ route('books.destroy', $book) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this book?')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection