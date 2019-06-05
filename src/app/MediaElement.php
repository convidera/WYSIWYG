<?php

namespace Convidera\WYSIWYG;

use Convidera\WYSIWYG\UuidModel;

class MediaElement extends UuidModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'media_path'
    ];

    /**
     * Get all of the owning models
     * like a homepage or content block
     * where this text element should be used.
     */
    public function mediaElementable() {
        return $this->morphTo();
    }

    public function scopeKey($query, $key)
    {
        $query->where('key', $key);
    }
}
