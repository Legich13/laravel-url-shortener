<?php
namespace Vendor\UrlShortener\Repositories;

use Vendor\UrlShortener\Models\ShortenedUrl;

class EloquentUrlRepository implements UrlRepositoryInterface
{
    public function create(string $longUrl): int
    {
        return ShortenedUrl::create([
            'long_url' => $longUrl,
        ])->id;
    }

    public function updateCode(int $id, string $code): void
    {
        ShortenedUrl::where('id', $id)->update(['code' => $code]);
    }

    public function findLongUrl(string $code): ?string
    {
        return ShortenedUrl::where('code', $code)->value('long_url');
    }

    public function incrementClicks(string $code): void
    {
        ShortenedUrl::where('code', $code)->increment('clicks');
    }

    public function getClickCount(string $code): int
    {
        return (int) ShortenedUrl::where('code', $code)->value('clicks', 0);
    }
} 