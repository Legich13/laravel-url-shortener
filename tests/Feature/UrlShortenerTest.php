<?php

namespace Vendor\UrlShortener\Tests\Feature;

use Vendor\UrlShortener\Facades\UrlShortener;
use Vendor\UrlShortener\Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    /** @test */
    public function it_can_shorten_url()
    {
        $longUrl = 'https://example.com/some/very/long/path';
        
        $code = UrlShortener::shorten($longUrl);
        
        $this->assertIsString($code);
        $this->assertNotEmpty($code);
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'code' => $code,
            'long_url' => $longUrl,
        ]);
    }
    
    /** @test */
    public function it_can_expand_url()
    {
        $longUrl = 'https://example.com/expand/this/url';
        $code = UrlShortener::shorten($longUrl);
        
        $result = UrlShortener::expand($code);
        
        $this->assertEquals($longUrl, $result);
    }
    
    /** @test */
    public function it_returns_null_for_non_existent_code()
    {
        $result = UrlShortener::expand('non-existent-code');
        
        $this->assertNull($result);
    }
    
    /** @test */
    public function it_increments_clicks_counter()
    {
        $longUrl = 'https://example.com/count/clicks';
        $code = UrlShortener::shorten($longUrl);
        
        // Изначально счетчик 0
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'code' => $code,
            'clicks' => 0,
        ]);
        
        UrlShortener::incrementClicks($code);
        
        // После инкремента счетчик 1
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'code' => $code,
            'clicks' => 1,
        ]);
    }
    
    /** @test */
    public function it_fails_validation_for_invalid_url()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        
        UrlShortener::shorten('not-a-valid-url');
    }
} 