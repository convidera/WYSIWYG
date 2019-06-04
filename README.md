# WYSIWYG

A basic and simple _**W**hat **Y**ou **S**ee **I**s **W**hat **Y**ou **G**et_ editor for text of homepages build with the php templating engine blade.

<!-- ToDo: https://poser.pugx.org/ -->

description ....  
to edit auth is needed

## Requirements
- PHP >= 
- Laravel >= 
- 

## Installation
```bash
composer require convidera/wysiwyg
```

For Laravel < 5.5 you need to add the service provider in your `config/app.php` manually.
```php
'providers' => [
    // ...
    Convidera\WYSIWYG\Providers\WYSIWYGServiceProvider::class,
    // ...
]
```

### Nova

If you also want to use nova. Create you own class `App\Nova\TextElement.php` which extends from `Convidera\WYSIWYG\Nova\TextElement` and add all class which use TextElements.

Example:
```php
<?php

namespace App\Nova;

use Convidera\WYSIWYG\Nova\TextElement as WYSIWYGTextElement;

class TextElement extends WYSIWYGTextElement
{
    public function getTextElementableTypes() {
        return [
            Homepage::class,
            StaticContent::class,
            ContentBlock::class,
            Slide::class,
        ];
    }
}
```

## Usage

### Model

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Convidera\WYSIWYG\Traits\HasDefaultTextContents;
use Convidera\WYSIWYG\Traits\ProvidesDefaultTextContents;

class Homepage extends Model implements ProvidesDefaultTextContents
{
    use HasDefaultTextContents;

    /**
     * The default text element keys that will be created after model creation.
     *
     * @var array
     */
    static protected $defaultTextKeys = [
        'headline',
        'text',
        // ...
    ];

    // ...
}
```

### Blade

#### Data
```html
{{ $data->headline }}
{{ $data->__('headline') }}
{{ $data->__('subModel.headline') }}
{{ $data->__('subModels.0.headline') }} <!-- array -->
```

#### Enable WYSIWYG feature

Blade directive:
```php
/**
 * @param data translation
 * @param tag surrounding tag
 * @param editable force normal text
 */
@text(data, tag = 'span', editable = true)
```

Normal usage:
```html
@text($data->headline)
@text($data->text)
```

For attributes and other usecases where only the data is needed and which is not able to be surrouded with a tag or editable:
```html
<input type="text" class="Form-input" v-model="email" placeholder="@text($data->__('notification.email'), null, false)">
```
