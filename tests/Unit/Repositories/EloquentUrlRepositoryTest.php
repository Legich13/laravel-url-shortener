<?php

namespace Vendor\UrlShortener\Tests\Unit\Repositories;

use Vendor\UrlShortener\Models\ShortenedUrl;
use Vendor\UrlShortener\Repositories\EloquentUrlRepository;
use Vendor\UrlShortener\Tests\TestCase;

class EloquentUrlRepositoryTest extends TestCase
{
    private EloquentUrlRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentUrlRepository();
    }

    /** @test */
    public function it_can_create_shortened_url()
    {
        $longUrl = 'https://example.com/very-long-url';
        $id = $this->repository->create($longUrl);

        $this->assertIsInt($id);
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'id' => $id,
            'long_url' => $longUrl,
        ]);
    }

    /** @test */
    public function it_can_update_code()
    {
        $longUrl = 'https://example.com/another-url';
        $id = $this->repository->create($longUrl);
        $code = 'test123';

        $this->repository->updateCode($id, $code);

        $this->assertDatabaseHas(config('url-shortener.table'), [
            'id' => $id,
            'code' => $code,
        ]);
    }

    /** @test */
    public function it_can_find_long_url_by_code()
    {
        $longUrl = 'https://example.com/find-me';
        $id = $this->repository->create($longUrl);
        $code = 'findme';
        $this->repository->updateCode($id, $code);

        $result = $this->repository->findLongUrl($code);

        $this->assertEquals($longUrl, $result);
    }

    /** @test */
    public function it_returns_null_for_non_existent_code()
    {
        $result = $this->repository->findLongUrl('non-existent');

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_increment_clicks()
    {
        $longUrl = 'https://example.com/click-me';
        $id = $this->repository->create($longUrl);
        $code = 'clickme';
        $this->repository->updateCode($id, $code);

        // начальное значение 0
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'id' => $id,
            'clicks' => 0,
        ]);

        $this->repository->incrementClicks($code);

        // после инкремента 1
        $this->assertDatabaseHas(config('url-shortener.table'), [
            'id' => $id,
            'clicks' => 1,
        ]);
    }
} 