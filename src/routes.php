<?php
use Illuminate\Support\Facades\Route;
use Vendor\UrlShortener\Http\Controllers\RedirectController;

Route::get(
    config('url-shortener.route_prefix') . config('url-shortener.redirect_path'),
    [RedirectController::class, 'handle']
)->name('url-shortener.redirect'); 