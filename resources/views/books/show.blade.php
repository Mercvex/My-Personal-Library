@extends('layouts.library')

@section('title', $book->title)

@section('content')
    <section class="page-heading">
        <h1>Book Details</h1>
        <p>View the details of your book collection.</p>
    </section>

    <div class="panel book-detail">
        @if ($book->cover_image)
            <img
                src="{{ asset('storage/' . $book->cover_image) }}"
                alt="Cover of {{ $book->title }}"
                class="book-detail-cover"
            >
        @else
            <div class="book-detail-cover book-cover-placeholder">
                No Cover Available
            </div>
        @endif

        <div class="book-detail-info">
            <h2>{{ $book->title }}</h2>
            <p class="muted">by {{ $book->author }}</p>

            <p><strong>Category:</strong> {{ $book->category ?: 'Not specified' }}</p>
            <p><strong>Reading Status:</strong> {{ $book->status }}</p>
            <p>
                <strong>Current Page:</strong>
                @if ($book->current_page !== null && $book->total_pages !== null)
                    {{ $book->current_page }} / {{ $book->total_pages }}
                    ({{ round($book->current_page / $book->total_pages * 100) }}%)
                @elseif ($book->current_page !== null)
                    {{ $book->current_page }} (total pages unknown)
                @else
                    Not specified
                @endif
            </p>

            @if ($book->total_pages !== null)
                <p><strong>Total Pages:</strong> {{ $book->total_pages }}</p>
            @endif

            <div class="book-summary">
                <h3>Short Summary</h3>
                <p>{{ $book->summary ?: 'No summary added yet.' }}</p>
            </div>

            <div class="book-file-section">
                <h3>Book File</h3>
                @if ($book->book_file_path)
                    <p>
                        <span class="file-info">{{ $book->original_filename }}</span>
                    </p>
                    <a href="{{ route('books.read', $book) }}" class="primary-btn" style="margin-top: 10px; display: inline-flex;">
                        📖 Read Book
                    </a>
                @else
                    <p class="muted">No book file uploaded yet.</p>
                    <a href="{{ route('books.edit', $book) }}" class="secondary-btn" style="margin-top: 8px; display: inline-flex;">
                        Upload a Book File
                    </a>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('books.index') }}" class="secondary-btn">Back to My Books</a>

                <form action="{{ route('books.favorite', $book) }}" method="POST">
                    @csrf
                    <button type="submit" class="secondary-btn">
                        {{ $isFavorite ? '★ Remove from Reading List' : '☆ Add to Reading List' }}
                    </button>
                </form>

                <a href="{{ route('books.edit', $book) }}" class="primary-btn">Edit Book</a>
            </div>
        </div>
    </div>
@endsection