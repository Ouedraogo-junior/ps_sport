<?php

use Illuminate\Support\Facades\Route;

// SEO
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// Controllers utilisateur
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendrierController;
use App\Http\Controllers\HomeController;

// Controllers admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\PaiementController as AdminPaiementController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AccessCodeController as AdminAccessCodeController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\GestionRetraitsController;



// Route pour le sitemap.xml
Route::get('/sitemap.xml', function () {
    return Sitemap::create()
        ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency('daily'))
        ->add(Url::create(route('performances'))->setPriority(0.8)->setChangeFrequency('daily'))
        ->add(Url::create(route('calendrier'))->setPriority(0.7)->setChangeFrequency('daily'))
        ->add(Url::create(route('register'))->setPriority(0.6)->setChangeFrequency('monthly'))
        ->toResponse(request());
})->name('sitemap');


// -------------------------------------------------------
// Routes publiques (sans connexion)
// -------------------------------------------------------

// Page d'accueil — vitrine publique avec performances et taux de réussite
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/calendrier/performances', [CalendrierController::class, 'performances'])->name('calendrier.performances');

// Calendrier des matchs du jour (LDC, PL, Liga, Ligue 1, Bundesliga, Serie A, CAN)
Route::get('/calendrier', [CalendrierController::class, 'index'])->name('calendrier');

// Page de performances publiques - captures gagnants
Route::get('/performances/captures', [CouponController::class, 'captures'])->name('performances.captures');

// Page de performances publique (historique + stats, levier SEO)
Route::get('/performances', [CouponController::class, 'performances'])->name('performances');

// -------------------------------------------------------
// Routes d'authentification (invités uniquement)
// -------------------------------------------------------

Route::middleware('guest')->group(function () {

    // Inscription
    Route::get('/inscription', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');

    // Connexion
    Route::get('/connexion', [LoginController::class, 'showForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store'])->name('login.store');

});

// Déconnexion (utilisateur connecté)
Route::post('/deconnexion', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// -------------------------------------------------------
// Routes utilisateur connecté (auth requis)
// -------------------------------------------------------

Route::middleware('auth')->group(function () {

    // Tableau de bord — statut abonnement + saisie code d'accès
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activation d'un abonnement via code d'accès
    Route::post('/dashboard/activer', [DashboardController::class, 'activerCode'])->name('dashboard.activer');

    // Soumission d'une demande de retrait
    Route::post('/dashboard/retrait', [DashboardController::class, 'demanderRetrait'])->name('dashboard.retrait');


    // -------------------------------------------------------
    // Routes nécessitant un abonnement actif
    // -------------------------------------------------------

    Route::middleware('abonnement')->group(function () {

        // Liste des coupons du jour
        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');

        // Détail d'un coupon
        Route::get('/coupons/{coupon}', [CouponController::class, 'show'])->name('coupons.show');

    });

    // Soumission d'un paiement (option A - formulaire de paiement manuel)
    Route::post('/dashboard/paiement', [DashboardController::class, 'soumettrePaiement'])->name('dashboard.paiement');

    // Page d'upgrade de plan
    Route::get('/upgrade-plan', [DashboardController::class, 'upgradePlan'])->name('dashboard.upgrade');

});

// -------------------------------------------------------
// Routes back-office admin (auth + admin requis)
// -------------------------------------------------------

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin — statistiques globales
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- Paiements ---
    Route::get('/paiements', [AdminPaiementController::class, 'index'])->name('paiements.index');
    Route::post('/paiements/{paiement}/valider', [AdminPaiementController::class, 'valider'])->name('paiements.valider');
    Route::post('/paiements/{paiement}/rejeter', [AdminPaiementController::class, 'rejeter'])->name('paiements.rejeter');

    // --- Codes d'accès ---
    Route::get('/codes', [AdminAccessCodeController::class, 'index'])->name('codes.index');
    Route::post('/codes', [AdminAccessCodeController::class, 'store'])->name('codes.store');
    Route::delete('/codes/{accessCode}/revoquer', [AdminAccessCodeController::class, 'revoquer'])->name('codes.revoquer');

    // --- Coupons ---
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/creer', [AdminCouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/editer', [AdminCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('/coupons/{coupon}/publier', [AdminCouponController::class, 'publier'])->name('coupons.publier');
    Route::post('/coupons/{coupon}/depublier', [AdminCouponController::class, 'depublier'])->name('coupons.depublier');
    Route::post('/coupons/{coupon}/resultat', [AdminCouponController::class, 'updateResultat'])->name('coupons.resultat');

    // --- Utilisateurs ---
    Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/{user}', [AdminUserController::class, 'show'])->name('utilisateurs.show');
    Route::post('/utilisateurs/{user}/bloquer', [AdminUserController::class, 'bloquer'])->name('utilisateurs.bloquer');
    Route::post('/utilisateurs/{user}/debloquer', [AdminUserController::class, 'debloquer'])->name('utilisateurs.debloquer');

    // --- Plans d'abonnement ---
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

    // --- Gestion des retraits ---
    Route::get('/retraits', [GestionRetraitsController::class, 'index'])->name('retraits.index');

    // --- Paramètres ---
    Route::get('/parametres', function () {
        return view('admin.parametres');
    })->name('parametres.index');

});