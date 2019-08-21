<?php

namespace Convidera\WYSIWYG;

use Convidera\WYSIWYG\Traits\ProvidesDefaultMediaElements;
use Convidera\WYSIWYG\UuidModel;
use Convidera\WYSIWYG\Traits\ProvidesDefaultTextElements;
use Convidera\WYSIWYG\Traits\HasDefaultTextElements;

class MediaElement extends UuidModel implements ProvidesDefaultTextElements
{
    use HasDefaultTextElements;
    
    /**
     * The default text element keys
     * which will create if a media element is created.
     * These text elements will be attributes of the media tag in html.
     * Key is attribute name and will be filled with the value.
     * 
     * e.g. $defaultTextKeys = [ 'alt' => 'My greate image!' ]
     *      => <img ... alt='My greate image!'></img>
     *
     * @var array
     */
   static protected $defaultTextKeys = [
       'alt',
       'title',
       'source'
   ];

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
    public function mediaElementable() {
        return $this->morphTo();
    }

    public function scopeKey($query, $key)
    {
        $query->where('key', $key);
    }

    public static function getMediaElementables()
    {
        return collect(self::$booted)->keys()->filter(function ($class) {
            return is_subclass_of($class, ProvidesDefaultMediaElements::class);
        });
    }
}
