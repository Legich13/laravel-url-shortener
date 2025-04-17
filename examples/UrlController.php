<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vendor\UrlShortener\Services\UrlShortenerService;

class UrlController extends Controller
{
    protected $urlShortener;
    
    public function __construct(UrlShortenerService $urlShortener)
    {
        $this->urlShortener = $urlShortener;
    }
    
    /**
     * Отображает форму для сокращения URL
     */
    public function index()
    {
        return view('url.index');
    }
    
    /**
     * Сокращает URL и возвращает короткий код
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'custom_code' => 'nullable|alpha_num|min:3|max:10'
        ]);
        
        // Сокращаем URL
        $code = $this->urlShortener->shorten($validated['url']);
        
        // Формируем полную короткую ссылку
        $shortUrl = url('u/' . $code);
        
        // Возвращаем результат
        return view('url.result', [
            'original_url' => $validated['url'],
            'short_code' => $code,
            'short_url' => $shortUrl
        ]);
    }
    
    /**
     * API метод для сокращения URL
     */
    public function shorten(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url'
        ]);
        
        $code = $this->urlShortener->shorten($validated['url']);
        $shortUrl = url('u/' . $code);
        
        return response()->json([
            'original_url' => $validated['url'],
            'short_code' => $code,
            'short_url' => $shortUrl
        ]);
    }
    
    /**
     * Перенаправляет на оригинальный URL
     */
    public function redirect($code)
    {
        $longUrl = $this->urlShortener->expand($code);
        
        if (!$longUrl) {
            abort(404, 'Короткая ссылка не найдена');
        }
        
        // Увеличиваем счетчик переходов
        $this->urlShortener->incrementClicks($code);
        
        // Перенаправляем на оригинальный URL
        return redirect()->away($longUrl);
    }
    
    /**
     * Возвращает статистику по коду
     */
    public function stats($code)
    {
        if (!$this->urlShortener->codeExists($code)) {
            abort(404, 'Короткая ссылка не найдена');
        }
        
        $longUrl = $this->urlShortener->expand($code);
        $clicks = $this->urlShortener->getClickCount($code);
        
        return view('url.stats', [
            'code' => $code,
            'long_url' => $longUrl,
            'clicks' => $clicks,
            'short_url' => url('u/' . $code)
        ]);
    }
} 