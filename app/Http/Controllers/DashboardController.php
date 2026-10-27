<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Efficient individual counts — no full collection load
        $totalBooks      = $user->books()->count();
        $currentlyReading = $user->books()->where('status', 'Currently Reading')->count();
        $finishedReading  = $user->books()->where('status', 'Finished Reading')->count();

        // Completion percentage: finished / total books
        $completionProgress = $totalBooks > 0
            ? round(($finishedReading / $totalBooks) * 100)
            : 0;

        // Average per-book reading progress across books that have both fields set
        $booksWithProgress = $user->books()
            ->whereNotNull('current_page')
            ->whereNotNull('total_pages')
            ->where('total_pages', '>', 0)
            ->selectRaw('ROUND(AVG(CAST(current_page AS FLOAT) / total_pages * 100)) as avg_progress')
            ->value('avg_progress');

        $avgReadingProgress = $booksWithProgress !== null ? (int) $booksWithProgress : null;

        // 4 most recently added books
        $recentBooks = $user->books()->latest()->limit(4)->get();

        // 4 favorited books for the reading list widget
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
