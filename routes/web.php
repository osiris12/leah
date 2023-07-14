<?php

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Scrapers\InglesScraper;
use App\Models\Word;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (InglesScraper $inglesScraper) {
    return Inertia::render('Welcome');
});

Route::get('/{word}', function (
    DictionaryController $dictionaryController,
    InglesScraper $inglesScraper,
    $word
) {
    if (Word::firstWhere('word', $word)) {
        return $dictionaryController->getFromDictionary($word);
    } else {
        return $inglesScraper->parseTranslationPage($word);
    }
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
