<?php
namespace Vendor\UrlShortener;

use Illuminate\Support\ServiceProvider;
use Vendor\UrlShortener\Generators\UrlGeneratorInterface;
use Vendor\UrlShortener\Generators\Base62Generator;
use Vendor\UrlShortener\Repositories\UrlRepositoryInterface;
use Vendor\UrlShortener\Repositories\EloquentUrlRepository;
use Vendor\UrlShortener\Services\UrlShortenerService;

class UrlShortenerServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Слияние конфигурации
        $this->mergeConfigFrom(__DIR__.'/../config/url-shortener.php', 'url-shortener');

        // Биндим интерфейсы на реализации
        $this->app->bind(UrlGeneratorInterface::class, Base62Generator::class);
        $this->app->bind(UrlRepositoryInterface::class, EloquentUrlRepository::class);

        // Сервис шортенера
        $this->app->singleton(UrlShortenerService::class, function($app) {
            return new UrlShortenerService(
                $app->make(UrlGeneratorInterface::class),
                $app->make(UrlRepositoryInterface::class)
            );
        });
    }

    public function boot()
    {
        // Публикация конфигурации
        $this->publishes([
            __DIR__.'/../config/url-shortener.php' => config_path('url-shortener.php'),
        ], 'config');

        // Публикация миграции
        $this->publishes([
            __DIR__.'/../database/migrations/create_shortened_urls_table.php.stub'
                => database_path('migrations/'.date('Y_m_d_His').'_create_shortened_urls_table.php'),
        ], 'migrations');

        // Загружаем маршруты только если в конфиге включено перенаправление
        if (config('url-shortener.enable_routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        }
    }
} 