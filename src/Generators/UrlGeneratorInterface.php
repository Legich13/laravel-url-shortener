<?php
namespace Vendor\UrlShortener\Generators;

interface UrlGeneratorInterface
{
    public function generate(int $id): string;
} 