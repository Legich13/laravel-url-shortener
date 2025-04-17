<?php

namespace Vendor\UrlShortener\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Vendor\UrlShortener\UrlShortenerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            UrlShortenerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Настройка окружения для тестов
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Загрузка миграций
        include_once __DIR__ . '/../database/migrations/create_shortened_urls_table.php.stub';
        (new \CreateShortenedUrlsTable())->up();
    }
} 