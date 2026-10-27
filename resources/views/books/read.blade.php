@extends('layouts.library')

@section('title', 'Reading: ' . $book->title)

@section('content')
    <div class="reader-header">
        <a href="{{ route('books.show', $book) }}" class="secondary-btn">
            ← Back to Book Details
        </a>
        <div>
            <h1 style="margin: 0; font-size: 20px; font-weight: 700;">{{ $book->title }}</h1>
            <p style="margin: 4px 0 0; color: var(--library-muted); font-size: 13px;">
                by {{ $book->author }}
            </p>
        </div>
    </div>

    <iframe
        src="{{ route('books.file', $book) }}"
        class="reader-frame"
        title="Reading: {{ $book->title }}"
    >
        <p class="muted">
            Your browser cannot display this file inline.
            <a href="{{ route('books.file', $book) }}">Download it instead.</a>
        </p>
    </iframe>
@endsection
