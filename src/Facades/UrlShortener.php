<?php
namespace Vendor\UrlShortener\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string shorten(string $longUrl)
 * @method static string|null expand(string $code)
 * @method static void incrementClicks(string $code)
 * @method static int getClickCount(string $code)
 * @method static bool codeExists(string $code)
 * 
 * @see \Vendor\UrlShortener\Services\UrlShortenerService
 */
class UrlShortener extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Vendor\UrlShortener\Services\UrlShortenerService::class;
    }
} 