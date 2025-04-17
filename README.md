# Laravel URL Shortener

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vendor/laravel-url-shortener.svg)](https://packagist.org/packages/vendor/laravel-url-shortener)
[![Total Downloads](https://img.shields.io/packagist/dt/vendor/laravel-url-shortener.svg)](https://packagist.org/packages/vendor/laravel-url-shortener)
[![Tests](https://github.com/vendor/laravel-url-shortener/actions/workflows/run-tests.yml/badge.svg)](https://github.com/vendor/laravel-url-shortener/actions/workflows/run-tests.yml)

SOLID-принципы в действии: простой и гибкий пакет для сокращения URL в Laravel.

## Установка

Установите пакет через Composer:

```bash
composer require vendor/laravel-url-shortener
```

### Публикация файлов

Опубликуйте конфигурацию и миграции:

```bash
php artisan vendor:publish --provider="Vendor\UrlShortener\UrlShortenerServiceProvider" --tag="config"
php artisan vendor:publish --provider="Vendor\UrlShortener\UrlShortenerServiceProvider" --tag="migrations"
```

Выполните миграции:

```bash
php artisan migrate
```

## Использование

### Сокращение URL с помощью фасада

```php
use Vendor\UrlShortener\Facades\UrlShortener;

// Сократить URL
$code = UrlShortener::shorten('https://example.com/long-path');

// Получить полную короткую ссылку
$shortUrl = url(config('url-shortener.route_prefix') . '/' . $code);
```

### Использование в контроллере

```php
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
    
    public function shorten(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url'
        ]);
        
        $code = $this->urlShortener->shorten($validated['url']);
        $shortUrl = url(config('url-shortener.route_prefix') . '/' . $code);
        
        return response()->json([
            'original_url' => $validated['url'],
            'short_code' => $code,
            'short_url' => $shortUrl
        ]);
    }
    
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
}
```

### Регистрация собственных маршрутов

Если вы отключили встроенные маршруты пакета (`'enable_routes' => false`), добавьте собственные в `routes/web.php`:

```php
Route::post('urls/shorten', [App\Http\Controllers\UrlController::class, 'shorten'])->name('urls.shorten');
Route::get('u/{code}', [App\Http\Controllers\UrlController::class, 'redirect'])->name('urls.redirect');
```

### Получение длинного URL из кода

```php
$longUrl = UrlShortener::expand($code);
```

### Подсчет переходов

Счетчик переходов увеличивается автоматически при каждом переходе или вручную:

```php
UrlShortener::incrementClicks($code);
```

## Настройка

Вы можете настроить пакет в файле `config/url-shortener.php`:

```php
return [
    // Имя таблицы для хранения
    'table' => 'shortened_urls',

    // Включить встроенные маршруты пакета
    'enable_routes' => true,

    // Префикс для всех коротких ссылок (можно пустым)
    'route_prefix' => '',

    // Путь для редиректа (например, '/go/{code}')
    'redirect_path' => '/{code}',
];
```

## Тестирование

```bash
composer test
```

## SOLID-принципы

Пакет разработан в соответствии с принципами SOLID:

1. **S** - Single Responsibility Principle: каждый класс имеет одну ответственность
2. **O** - Open/Closed Principle: расширение без модификации через интерфейсы
3. **L** - Liskov Substitution Principle: взаимозаменяемость реализаций через интерфейсы
4. **I** - Interface Segregation Principle: интерфейсы с минимальным набором методов
5. **D** - Dependency Inversion Principle: зависимость от абстракций, а не от конкретных реализаций

## License

The MIT License (MIT). См. [License File](LICENSE.md) для дополнительной информации. 