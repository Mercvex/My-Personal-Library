@extends('layouts.library')

@section('title', 'Edit Book')

@section('content')
    <section class="page-heading">
        <h1>Edit Book</h1>
        <p>Update the details of a book in your personal library.</p>
    </section>

    <div class="panel">
        <form
            action="{{ route('books.update', $book) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Book Title *</label>
                <input
                    class="form-control"
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $book->title) }}"
                    placeholder="Enter the book title"
                    required
                >
                @error('title')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="author">Author *</label>
                <input
                    class="form-control"
                    type="text"
                    id="author"
                    name="author"
                    value="{{ old('author', $book->author) }}"
                    placeholder="Enter the author's name"
                    required
                >
                @error('author')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <input
                    class="form-control"
                    type="text"
                    id="category"
                    name="category"
                    value="{{ old('category', $book->category) }}"
                    placeholder="e.g. Fiction, Self-growth"
                >
                @error('category')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Reading Status *</label>
                <select class="form-control" id="status" name="status" required>
                    <option
                        value="Currently Reading"
                        {{ old('status', $book->status) === 'Currently Reading' ? 'selected' : '' }}
                    >
                        Currently Reading
                    </option>
                    <option
                        value="Finished Reading"
                        {{ old('status', $book->status) === 'Finished Reading' ? 'selected' : '' }}
                    >
                        Finished Reading
                    </option>
                </select>
                @error('status')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="cover_image">Book Cover <span class="muted">(Optional)</span></label>

                @if ($book->cover_image)
                    <div class="current-cover">
                        <p class="muted">Current cover:</p>
                        <img
                            src="{{ asset('storage/' . $book->cover_image) }}"
                            alt="Cover of {{ $book->title }}"
                            class="edit-cover-preview"
                        >
                    </div>
                @endif

                <input
                    class="form-control"
                    type="file"
                    id="cover_image"
                    name="cover_image"
                    accept="image/png,image/jpeg,image/jpg"
                >
                <p class="muted">PNG or JPG, maximum 2 MB. Leave empty to keep the current cover.</p>
                @error('cover_image')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="current_page">Current Page <span class="muted">(Optional)</span></label>
                <input
                    class="form-control"
                    type="number"
                    id="current_page"
                    name="current_page"
                    min="1"
                    value="{{ old('current_page', $book->current_page) }}"
                    placeholder="Enter your current page"
                >
                @error('current_page')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="summary">Short Summary <span class="muted">(Optional)</span></label>
                <textarea
                    class="form-control"
                    id="summary"
                    name="summary"
                    rows="5"
                    placeholder="Write a short summary about this book..."
                >{{ old('summary', $book->summary) }}</textarea>
                @error('summary')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('books.index') }}" class="secondary-btn">Cancel</a>
                <button type="submit" class="primary-btn">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
