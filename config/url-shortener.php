<?php
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