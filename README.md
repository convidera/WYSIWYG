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
If this Project is in a public repository:
```bash
composer require convidera/wysiwyg
```
If this Project is in a private repository:

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
      - ~/.ssh:/var/www/.ssh
```
or
```yaml
volumes:
     - ~/.ssh/id_rsa:/var/www/.ssh/id_rsa
     - ~/.ssh/id_rsa.pub:/var/www/.ssh/id_rsa.pub
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
// ('key', \$var)                            ->  "$var->xxxx('key')"
// ('key', [ 'options' => true ])            ->  "$data->xxxx('key', [ "options" => true ])"
// ('key', \$var, [ 'options' => true ])     ->  "$var->xxxx('key', [ "options" => true ])"
// ("key")                                   ->  "$data->xxxx('key')"
// ("key", $var)                             ->  "$var->xxxx('key')"
// ("key", [ "options" => true ])            ->  "$data->xxxx('key', [ "options" => true ])"
// ("key", $vra, [ "options" => true ])      ->  "$var->xxxx('key', [ "options" => true ])"

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
@text($data->__('headline'))
@text($data->__('headline'), [ /* options */ ])
````


Normal usage:
```html
@text('headline')
@text('text')
```

For attributes and other usecases where only the data is needed and which is not able to be surrouded with a tag or editable:
```html
<input type="text" class="Form-input" v-model="email" placeholder="$data->text('notification.email')">
```
