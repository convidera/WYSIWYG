# WYSIWYG

A basic and simple _**W**hat **Y**ou **S**ee **I**s **W**hat **Y**ou **G**et_ editor for text of homepages build with the php templating engine blade.

<!-- ToDo: https://poser.pugx.org/ -->

## What does you get
If installed and implemented WYSIWYG in your project you only have to log in. Then the following commands will be avalible:

| cmd | description |
|--|--|
| I | enable edit -> insert mode (unless if already in insert mode) |
| CRTL+E or CMD+E | toggle insert mode |
| ESC | disable insert mode -> normal mode |
| CRTL+E or CMD+E | toggle placeholder mode |
| CRTL+S or CMD+S | save element in focus; otherwise ask user to save all |
| CRTL+SHIFT+S or CMD+SHIFT+S | save all |
| 2x SHIFT | display borders around all WYSIWYG elements for a few seconds |

**Note**: CRTL will be the CMD/META key for mac user.


## Requirements
- PHP >= 7.3
- Laravel >= 5.5


## Installation
If this project is in a public repository:
```bash
composer require convidera/wysiwyg
```

If this project is in a private repository:  
The `composer.json` either has to reference the git repository
```json
"repositories": [
    {
        ...
    },
    {
        "type": "git",
        "url": "git@github.com:convidera/WYSIWYG.git"
    }
]
```
or the local path
```json
"repositories": [
    {
        ...
    },
    {
        "type": "path",
        "url": "../WYSIWYG.git"
    }
]
```
which might has to be included into the app docker container or installed from outside the box.

For readaccess to the private git repository the native machines ssh key added to the `docker-compose.override.yml` and the `docker-compose.ci.yml`.

```yaml
volumes:
  - ~/.ssh:/var/www/.ssh:ro
```
or
```yaml
volumes:
  - ~/.ssh/id_rsa:/var/www/.ssh/id_rsa:ro
  - ~/.ssh/id_rsa.pub:/var/www/.ssh/id_rsa.pub:ro
  - ~/.ssh/known_hosts:/var/www/.ssh/known_hosts
```


## Setup

For Laravel < 5.5 you need to add the service provider in your `config/app.php` manually.
```php
'providers' => [
    // ...
    Convidera\WYSIWYG\Providers\WYSIWYGServiceProvider::class,
    // ...
]
```

### Nova

#### Text Elements
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

#### Media Elements
The same goes for Media elements:  
class `App\Nova\MediaElement.php` which extends from `Convidera\WYSIWYG\Nova\MediaElement`

Example:
```php
<?php

namespace App\Nova;

use Convidera\WYSIWYG\Nova\MediaElement as WYSIWYGMediaElement;

class MediaElement extends WYSIWYGMediaElement
{
    public function getMediaElementableTypes() {
        return [
            Homepage::class,
            ContentBlock::class,
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
use Convidera\WYSIWYG\Traits\ProvidesDefaultMediaElements;
use Convidera\WYSIWYG\Traits\ProvidesDefaultTextContents;

class Homepage extends Model implements ProvidesDefaultTextElements, ProvidesDefaultMediaElements
{
    use HasDefaultTextElements, HasDefaultMediaElements;

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

    /**
     * The default media element keys that will be created after model creation.
     *
     * @var array
     */
    static protected $defaultMediaKeys = [
        'media',
        // ...
    ];

    // ...
}
```

### Blade

#### Data
```html
<!-- element data object -->
{{ $data->headline }}

<!-- element data value -->
{{ $data->__('headline') }}
{{ $data->__('subModel.headline') }}    <!-- sub-model -->
{{ $data->__('subModels.0.headline') }} <!-- array -->
```

#### Enable WYSIWYG feature
Blade directive:
```php
/**
 * @param {string} $key      text element key
 * @param {string} $var      varibale where the key is stored (default: $data)
 * @param {array}  $options  custom display options e.g.: [
 *                                  'tag' => 'span',
 *                                  'editable' => true
 *                              ];
 */
@text($key, $var, $options)
/**
 * @param {object} $data     text element data
 * @param {array}  $options  custom display options e.g.: [
 *                                  'tag' => 'span',
 *                                  'editable' => true
 *                              ];
 */
@textraw($data, $options)

/**
 * @param {string} $key      text element key
 * @param {string} $var      varibale where the key is stored (default: $data)
 * @param {array}  $options  custom display options e.g.: [
 *                                  'tag' => 'span',
 *                                  'editable' => true
 *                              ];
 */
@markdown($key, $var, $options)
/**
 * @param {object} $data     text element data
 * @param {array}  $options  custom display options e.g.: [
 *                                  'tag' => 'span',
 *                                  'editable' => true
 *                              ];
 */
@markdownraw($data, $options)

/**
 * @param {string} $key      image element key
 * @param {string} $var      varibale where the key is stored (default: $data)
 * @param {array}  $options  custom display options e.g.: [
 *                                  'tag' => 'img',
 *                                  'editable' => true,
 *                                  'asBackgroundImage' => false,
 *                                  'closeTag' => true
 *                              ];
 */
@image($key, $var, $options)
/**
 * @param {object} $data     image element data
 * @param {array}  $options  custom display options e.g.: [
 *                                  'tag' => 'img',
 *                                  'editable' => true,
 *                                  'asBackgroundImage' => false,
 *                                  'closeTag' => true
 *                              ];
 */
@imageraw($data, $options)
```

Blade call posibilities (without *raw):
```php
@text(...)
@markdown(...)
@image(...)

// xxxx e.g.: mediaElement, textElement etc. (source Response)
// ('key')                                   ->  "$data->xxxx('key')"
// ('key', $var)                             ->  "$var->xxxx('key')"
// ('key', [ 'options' => true ])            ->  "$data->xxxx('key', [ "options" => true ])"
// ('key', $var, [ 'options' => true ])      ->  "$var->xxxx('key', [ "options" => true ])"
// ("key")                                   ->  "$data->xxxx('key')"
// ("key", $var)                             ->  "$var->xxxx('key')"
// ("key", [ "options" => true ])            ->  "$data->xxxx('key', [ "options" => true ])"
// ("key", $var, [ "options" => true ])      ->  "$var->xxxx('key', [ "options" => true ])"

// examples
@text('headline')
@markdown('content', $page)
@image('backgound', [
    'tag' => 'header',
    'asBackgroundImage' => true
])
@image('backgound', $page, [
    'tag' => 'div',
    'asBackgroundImage' => true,
    'closeTag' => false
]) @endimage
```

Blade call posibilities (with *raw):
```php
@textraw(...)
@markdownraw(...)
@imageraw(...)

// examples:
@textraw($data->headline)
@textraw($data->textElement('headline'), [ /* options */ ])
```

Normal/Default usages:
```html
@text('headline')
@text('text')
@markdown('content')
@image('media')

<!-- extended foreach example -->
@foreach($data->productCategories as $key => $productCategory)
    <div class="Categories__slide">
        @image('media', $productCategory, [ 'additionalClasses' => 'slick-slide__img' ])
        <div class="slick-name">
            @text('name', $productCategory)
        </div>
    </div>
@endforeach
```

For attributes and other usecases where only the data is needed and which is not able to be surrouded with a tag or editable:
```html
<input type="text" class="Form-input" v-model="email" placeholder="{{ $data->__('notification.email') }}">
```
