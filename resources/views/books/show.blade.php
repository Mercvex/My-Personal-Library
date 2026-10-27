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
            <p><strong>Current Page:</strong> {{ $book->current_page ?: 'Not specified' }}</p>

            <div class="book-summary">
                <h3>Short Summary</h3>
                <p>{{ $book->summary ?: 'No summary added yet.' }}</p>
            </div>

            <div class="form-actions">
                <a href="{{ route('books.index') }}" class="secondary-btn">Back to My Books</a>
                <a href="{{ route('books.edit', $book) }}" class="primary-btn">Edit Book</a>
            </div>
        </div>
    </div>
@endsection