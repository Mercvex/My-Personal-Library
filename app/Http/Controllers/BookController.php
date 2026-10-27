<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index()
    {
        $books = auth()->user()
            ->books()
            ->latest()
            ->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => [
                'required',
                Rule::in(['Currently Reading', 'Finished Reading']),
            ],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'current_page' => ['nullable', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('book-covers', 'public');
        }

        auth()->user()->books()->create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully.');
    }

    public function show(Book $book)
    {
        $this->authorizeBook($book);

        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $this->authorizeBook($book);

        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorizeBook($book);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => [
                'required',
                Rule::in(['Currently Reading', 'Finished Reading']),
            ],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'current_page' => ['nullable', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store('book-covers', 'public');
        }

        $book->update($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->authorizeBook($book);

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }

    private function authorizeBook(Book $book): void
    {
        abort_unless(
            $book->user_id === auth()->id(),
            403
        );
    }
}
