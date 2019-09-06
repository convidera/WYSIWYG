<?php

namespace Convidera\WYSIWYG;

use Convidera\WYSIWYG\Traits\ProvidesDefaultTextElements;
use Convidera\WYSIWYG\UuidModel;

class TextElement extends UuidModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Get all of the owning models
     * like a homepage or content block
     * where this text element should be used.
     */
    public function textElementable() {
        return $this->morphTo();
    }

    public function scopeKey($query, $key)
    {
        $query->where('key', $key);
    }
}
