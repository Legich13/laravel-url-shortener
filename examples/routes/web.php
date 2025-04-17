<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

/*
|--------------------------------------------------------------------------
| Маршруты для сокращения ссылок
|--------------------------------------------------------------------------
*/

// Отображение формы сокращения URL
Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');

// Обработка формы и создание короткой ссылки
Route::post('/urls', [UrlController::class, 'store'])->name('urls.store');

// API для сокращения URL
Route::post('/api/urls', [UrlController::class, 'shorten'])->name('api.urls.shorten');

// Статистика по короткой ссылке
Route::get('/u/{code}/stats', [UrlController::class, 'stats'])->name('urls.stats');

// Перенаправление по короткой ссылке
Route::get('/u/{code}', [UrlController::class, 'redirect'])->name('urls.redirect'); 