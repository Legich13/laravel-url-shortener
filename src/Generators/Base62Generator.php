<?php
namespace Vendor\UrlShortener\Generators;

class Base62Generator implements UrlGeneratorInterface
{
    private string $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    public function generate(int $id): string
    {
        $base = strlen($this->alphabet);
        $code = '';
        do {
            $code = $this->alphabet[$id % $base] . $code;
            $id = intdiv($id, $base);
        } while ($id > 0);

        return $code;
    }
} 