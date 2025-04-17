<?php
namespace Vendor\UrlShortener\Models;

use Illuminate\Database\Eloquent\Model;

class ShortenedUrl extends Model
{
    protected $table = '';
    protected $fillable = ['code', 'long_url', 'clicks'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // берём имя таблицы из конфига
        $this->setTable(config('url-shortener.table'));
    }
} 