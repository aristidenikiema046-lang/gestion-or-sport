<?php

use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(auth()->user()->homeRouteName());
})->name('home');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/creer', [CommandeController::class, 'create'])->name('commandes.create');
    Route::get('/commandes/livrees', [CommandeController::class, 'livrees'])->name('commandes.livrees');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::get('/commandes/{commande}/modifier', [CommandeController::class, 'edit'])->name('commandes.edit');
    Route::patch('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');
    Route::patch('/commandes/{commande}/livrer', [CommandeController::class, 'marquerLivree'])->name('commandes.livrer');
    Route::get('/commandes/{commande}/bon-livraison', [CommandeController::class, 'bonLivraison'])->name('commandes.bon-livraison');
    Route::get('/commandes/{commande}/bon-livraison/pdf', [CommandeController::class, 'bonLivraisonPdf'])->name('commandes.bon-livraison.pdf');
    Route::post('/notifications/push-subscribe', [PushSubscriptionController::class, 'store'])->name('notifications.push-subscribe');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Lien de partage public du bon de livraison — volontairement hors
// authentification, mais résolu par jeton opaque (jamais par id) pour
// qu'une commande ne puisse être consultée qu'avec son propre lien.
Route::get('/bon-livraison/{commande:partage_token}', [CommandeController::class, 'bonLivraison'])
    ->name('commandes.bon-livraison.public');
Route::get('/bon-livraison/{commande:partage_token}/pdf', [CommandeController::class, 'bonLivraisonPdf'])
    ->name('commandes.bon-livraison.pdf.public');

require __DIR__.'/auth.php';
