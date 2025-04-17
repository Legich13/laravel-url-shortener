<?php
namespace Vendor\UrlShortener\Http\Controllers;

use Illuminate\Routing\Controller;
use Vendor\UrlShortener\Services\UrlShortenerService;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handle(Request $request, UrlShortenerService $service, string $code)
    {
        $long = $service->expand($code);

        if (! $long) {
            abort(404, 'Short URL not found.');
        }

        // увеличить счётчик через метод сервиса
        $service->incrementClicks($code);

        return redirect()->to($long, 301);
    }
} 