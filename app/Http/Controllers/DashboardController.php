<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $totalBooks = $user->books()->count();
        $currentlyReading = $user->books()->where('status', 'Currently Reading')->count();
        $finishedReading = $user->books()->where('status', 'Finished Reading')->count();

        $completionProgress = $totalBooks > 0
            ? round(($finishedReading / $totalBooks) * 100)
            : 0;

        $booksWithProgress = $user->books()
            ->whereNotNull('current_page')
            ->whereNotNull('total_pages')
            ->where('total_pages', '>', 0)
            ->selectRaw('ROUND(AVG(CAST(current_page AS FLOAT) / total_pages * 100)) as avg_progress')
            ->value('avg_progress');

        $avgReadingProgress = $booksWithProgress !== null ? (int) $booksWithProgress : null;

        $recentBooks = $user->books()->latest()->limit(4)->get();

        $favoriteBooks = $user->favorites()->latest('favorites.book_id')->limit(4)->get();

        return view('dashboard', compact(
            'totalBooks',
            'currentlyReading',
            'finishedReading',
            'completionProgress',
            'avgReadingProgress',
            'recentBooks',
            'favoriteBooks',
        ));
    }
}
