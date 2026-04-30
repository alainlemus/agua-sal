<?php

use App\Http\Controllers\SEO\RobotsController;
use App\Http\Controllers\SEO\SitemapController;
use Illuminate\Support\Facades\Route;

use App\Livewire\HomePage;
use App\Livewire\MenuPage;
use App\Livewire\GenericPage;
use App\Livewire\ReviewForm;
use App\Livewire\ReviewFormPublic;
use App\Livewire\PrivacyPage;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/', HomePage::class);
Route::get('/menu', MenuPage::class)->name('menu');

// QR permanente de campaña — URL fija, busca la campaña activa automáticamente
Route::get('/resena/activa', ReviewFormPublic::class)->name('review.public');

// Formulario de reseña con regalo — requiere token de un solo uso generado por el mesero
Route::get('/resena/{token}', ReviewForm::class)->name('review.form');

// QR directo al menú — alias que redirige a /menu
Route::get('/qr/menu', fn () => redirect('/menu'))->name('qr.menu');

// Vista previa de página CMS — URL firmada, expira en 30 min, no requiere publicación
Route::get('/preview/{slug}', GenericPage::class)
    ->name('page.preview')
    ->middleware('signed');

// Aviso de Privacidad — página fija, contenido editable desde Configuración General
Route::get('/aviso-de-privacidad', PrivacyPage::class)->name('privacy');

// Catch-all: cualquier página creada en el CMS se sirve automáticamente por su slug
Route::get('/{slug}', GenericPage::class)
    ->where('slug', '^(?!admin|livewire|storage|_debugbar|resena|qr|preview)[a-z0-9\-\_]+$');

