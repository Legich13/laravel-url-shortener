<?php
namespace Vendor\UrlShortener\Services;

use Vendor\UrlShortener\Generators\UrlGeneratorInterface;
use Vendor\UrlShortener\Repositories\UrlRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UrlShortenerService
{
    public function __construct(
        private UrlGeneratorInterface  $generator,
        private UrlRepositoryInterface $repository
    ) {}

    /**
     * Создает сокращенную ссылку
     * 
     * @param string $longUrl Длинный URL для сокращения
     * @return string Короткий код
     * @throws ValidationException
     */
    public function shorten(string $longUrl): string
    {
        // 1) Проверка валидности
        Validator::make(
            ['url' => $longUrl],
            ['url' => 'required|url']
        )->validate();

        // 2) Сохраняем и получаем ID
        $id = $this->repository->create($longUrl);

        // 3) Генерируем код
        $code = $this->generator->generate($id);

        // 4) Обновляем запись кодом
        $this->repository->updateCode($id, $code);

        return $code;
    }

    /**
     * Получает длинный URL по короткому коду
     * 
     * @param string $code Короткий код
     * @return string|null Длинный URL или null, если код не найден
     */
    public function expand(string $code): ?string
    {
        return $this->repository->findLongUrl($code);
    }
    
    /**
     * Увеличивает счетчик кликов для сокращенной ссылки
     * 
     * @param string $code Короткий код
     * @return void
     */
    public function incrementClicks(string $code): void
    {
        $this->repository->incrementClicks($code);
    }
    
    /**
     * Получает количество кликов по ссылке
     * 
     * @param string $code Короткий код
     * @return int Количество переходов
     */
    public function getClickCount(string $code): int
    {
        return $this->repository->getClickCount($code);
    }
    
    /**
     * Проверяет существование короткого кода
     * 
     * @param string $code Короткий код
     * @return bool Существует ли код
     */
    public function codeExists(string $code): bool
    {
        return $this->repository->findLongUrl($code) !== null;
    }
} 