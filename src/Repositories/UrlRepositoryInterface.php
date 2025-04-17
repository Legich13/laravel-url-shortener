<?php
namespace Vendor\UrlShortener\Repositories;

interface UrlRepositoryInterface
{
    public function create(string $longUrl): int;
    public function updateCode(int $id, string $code): void;
    public function findLongUrl(string $code): ?string;
    public function incrementClicks(string $code): void;
    public function getClickCount(string $code): int;
} 