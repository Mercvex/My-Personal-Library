@extends('layouts.library')

@section('title', 'Add a New Book')

@section('content')
    <section class="page-heading">
        <h1>Add a New Book</h1>
        <p>Add a book to your personal library.</p>
    </section>

    <div class="panel">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Book Title *</label>
                <input
                    class="form-control"
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
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
                    value="{{ old('author') }}"
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
                    value="{{ old('category') }}"
                    placeholder="e.g. Fiction, Self-growth"
                >
                @error('category')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Reading Status *</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Currently Reading" {{ old('status', 'Currently Reading') === 'Currently Reading' ? 'selected' : '' }}>
                        Currently Reading
                    </option>
                    <option value="Finished Reading" {{ old('status') === 'Finished Reading' ? 'selected' : '' }}>
                        Finished Reading
                    </option>
                </select>
                @error('status')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="cover_image">Book Cover <span class="muted">(Optional)</span></label>
                <input
                    class="form-control"
                    type="file"
                    id="cover_image"
                    name="cover_image"
                    accept="image/png,image/jpeg,image/jpg"
                >
                <p class="muted">PNG or JPG, maximum 2 MB.</p>
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
                    value="{{ old('current_page') }}"
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
                >{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="secondary-btn">Cancel</a>
                <button type="submit" class="primary-btn">Add Book</button>
            </div>
        </form>
    </div>
@endsection