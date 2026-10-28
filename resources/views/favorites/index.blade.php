@extends('layouts.library')

@section('title', 'My Favorites')

@section('content')
    <section class="page-heading">
        <h1>My Favorites</h1>
        <p>Books you have starred in your personal collection.</p>
    </section>

    @if ($books->isEmpty())
        <div class="panel empty-books">
            <h2>No favorites yet</h2>
            <p class="muted">
                Star a book on the My Books page or its detail page to add it here.
            </p>
            <a href="{{ route('books.index') }}" class="primary-btn">Browse My Books</a>
        </div>
    @else
        <div class="books-grid">
            @foreach ($books as $book)
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

                            {{-- Toggle favorite — returns back() to this page, removing the card --}}
                            <form action="{{ route('books.favorite', $book) }}" method="POST">
                                @csrf
                                <button type="submit" title="Remove from favorites">
                                    ★
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
