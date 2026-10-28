<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FavoritesController extends Controller
{
    public function index(): View
    {
        $books = auth()->user()
            ->favorites()
            ->latest('favorites.book_id')
            ->get();

        return view('favorites.index', compact('books'));
    }
}
