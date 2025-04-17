<?php

namespace Vendor\UrlShortener\Tests\Feature;

use Illuminate\Http\Request;
use Vendor\UrlShortener\Facades\UrlShortener;
use Vendor\UrlShortener\Http\Controllers\RedirectController;
use Vendor\UrlShortener\Tests\TestCase;

class RedirectControllerTest extends TestCase
{
    /** @test */
    public function it_redirects_to_long_url()
    {
        $longUrl = 'https://example.com/redirect/here';
        $code = UrlShortener::shorten($longUrl);
        
        $response = $this->get(config('url-shortener.route_prefix') . '/' . $code);
        
        $response->assertStatus(301);
        $response->assertRedirect($longUrl);
    }
    
    /** @test */
    public function it_returns_404_for_non_existent_code()
    {
        $response = $this->get(config('url-shortener.route_prefix') . '/non-existent-code');
        
        $response->assertStatus(404);
    }
    
    /** @test */
    public function it_increments_clicks_when_redirecting()
    {
        $longUrl = 'https://example.com/count-on-redirect';
        $code = UrlShortener::shorten($longUrl);
        
        // До перехода счетчик 0
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'code' => $code,
            'clicks' => 0,
        ]);
        
        // Выполняем редирект
        $this->get(config('url-shortener.route_prefix') . '/' . $code);
        
        // После перехода счетчик 1
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'code' => $code,
            'clicks' => 1,
        ]);
    }
} 