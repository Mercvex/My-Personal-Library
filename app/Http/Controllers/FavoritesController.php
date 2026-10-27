<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FavoritesController extends Controller
{
    public function index(): View
    {
        // Load only books this user has favorited, via the pivot relationship.
        // Eager-load the pivot so the ★ toggle button works without extra queries.
        $books = auth()->user()
            ->favorites()
            ->latest('favorites.book_id')
            ->get();

        return view('favorites.index', compact('books'));
    }
}
