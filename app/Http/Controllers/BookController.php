<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();
        $status = $request->string('status')->trim()->value();
        $favorite = $request->boolean('favorite');

        $books = auth()->user()
            ->books()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('author', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%');
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($favorite, function ($query) {
                $query->whereHas('favoritedBy', function ($q) {
                    $q->where('users.id', auth()->id());
                });
            })
            ->latest()
            ->get();

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Currently Reading', 'Finished Reading'])],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'book_file' => ['nullable', 'file', 'mimes:pdf,txt', 'max:20480'],
            'current_page' => ['nullable', 'integer', 'min:0'],
            'total_pages' => ['nullable', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
        ]);

        if (
            isset($validated['current_page'], $validated['total_pages']) &&
            $validated['current_page'] > $validated['total_pages']
        ) {
            return back()
                ->withInput()
                ->withErrors(['current_page' => 'Current page cannot exceed the total number of pages.']);
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('book-covers', 'public');
        }

        if ($request->hasFile('book_file')) {
            $file = $request->file('book_file');
            $validated['book_file_path'] = $file->store('book-files', 'local');
            $validated['original_filename'] = $file->getClientOriginalName();
        }

        // Remove the raw upload key — not a DB column
        unset($validated['book_file']);

        auth()->user()->books()->create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully.');
    }

    public function show(Book $book): View
    {
        $this->authorizeBook($book);

        $isFavorite = auth()->user()->favorites()->where('book_id', $book->id)->exists();

        return view('books.show', compact('book', 'isFavorite'));
    }

    public function edit(Book $book): View
    {
        $this->authorizeBook($book);

        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $this->authorizeBook($book);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['Currently Reading', 'Finished Reading'])],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'book_file' => ['nullable', 'file', 'mimes:pdf,txt', 'max:20480'],
            'current_page' => ['nullable', 'integer', 'min:0'],
            'total_pages' => ['nullable', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
        ]);

        if (
            isset($validated['current_page'], $validated['total_pages']) &&
            $validated['current_page'] > $validated['total_pages']
        ) {
            return back()
                ->withInput()
                ->withErrors(['current_page' => 'Current page cannot exceed the total number of pages.']);
        }

        // --- Cover image replacement ---
        $oldCover = $book->cover_image;
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('book-covers', 'public');
        }

        // --- Book file replacement ---
        // Store the new file before touching the DB
        $oldBookFilePath = $book->book_file_path;
        $newBookFilePath = null;
        if ($request->hasFile('book_file')) {
            $file = $request->file('book_file');
            $newBookFilePath = $file->store('book-files', 'local');
            $validated['book_file_path'] = $newBookFilePath;
            $validated['original_filename'] = $file->getClientOriginalName();
        }

        // Remove the raw upload key — not a DB column
        unset($validated['book_file']);

        // Persist to DB
        $book->update($validated);

        // Clean up old files only after a successful DB update
        if ($request->hasFile('cover_image') && $oldCover) {
            Storage::disk('public')->delete($oldCover);
        }
        if ($newBookFilePath && $oldBookFilePath) {
            Storage::disk('local')->delete($oldBookFilePath);
        }

        return redirect()
            ->route('books.show', $book)
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->authorizeBook($book);

        $coverPath = $book->cover_image;
        $bookFilePath = $book->book_file_path;

        $book->delete();

        // Delete files only after the DB record is gone
        if ($coverPath) {
            Storage::disk('public')->delete($coverPath);
        }
        if ($bookFilePath) {
            Storage::disk('local')->delete($bookFilePath);
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }

    public function toggleFavorite(Book $book): RedirectResponse
    {
        $this->authorizeBook($book);

        $user = auth()->user();

        if ($user->favorites()->where('book_id', $book->id)->exists()) {
            $user->favorites()->detach($book->id);
            $message = 'Removed from your reading list.';
        } else {
            $user->favorites()->attach($book->id);
            $message = 'Added to your reading list.';
        }

        return back()->with('success', $message);
    }

    /**
     * Show the in-browser reader page for a book.
     * The actual file is served by serveFile() through a protected route.
     */
    public function read(Book $book): View|RedirectResponse
    {
        $this->authorizeBook($book);

        if (! $book->book_file_path) {
            return redirect()
                ->route('books.show', $book)
                ->with('success', 'This book has no uploaded file yet. You can add one by editing the book.');
        }

        return view('books.read', compact('book'));
    }

    /**
     * Stream the private book file to the authenticated owner's browser.
     * The file path is never exposed in the response.
     */
    public function serveFile(Book $book): Response
    {
        $this->authorizeBook($book);

        abort_if(! $book->book_file_path, 404);
        abort_unless(Storage::disk('local')->exists($book->book_file_path), 404);

        // Determine content type from the stored extension
        $extension = strtolower(pathinfo($book->book_file_path, PATHINFO_EXTENSION));
        $contentType = $extension === 'pdf' ? 'application/pdf' : 'text/plain; charset=utf-8';

        $fileContents = Storage::disk('local')->get($book->book_file_path);

        return response($fileContents, 200)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'inline; filename="'.addslashes($book->original_filename ?? 'book.'.$extension).'"')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Cache-Control', 'private, no-store');
    }

    private function authorizeBook(Book $book): void
    {
        abort_unless(
            $book->user_id === auth()->id(),
            403
        );
    }
}
