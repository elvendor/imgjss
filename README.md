# imgjss Laravel Package

[Imgjss](https://github.com/elvendor/imgjss) is a simple [Laravel 4](http://laravel.com) package that provides handy way of including assets into your `.blade.php` files.
Now you can use `HTML::style()`, `HTML::script()` and `HTML::image()` via short Blade syntax.

## Features


Avoid putting extensions of **js** & **css** files, package adds it automatically:
```php
@js('js/jquery.js')
```
same as:
```php
@js('js/jquery')
```

But as for images, you **should** use file extensions:

```php
@img('uploads/cat.jpg')
```

Pass attributes array as second parameter:
```php
@css('css/print', ['media' => 'print'])
```

By default package automatically appends last modified timestamp of the file as query string.
You can change/disable this behavior in config file or simply pass true/false as third parameter:
```php
@js('js/translations', ['charset' => 'utf-8'], false) // disabled timestamp appending
```

When using Laravel's `HTML::image()` you pass `alt` attribute as second param, and other attributes as third.
I think that is not good idea. Using `@img` syntax this package provides you can pass all in one array:
```php
@img('img/logo.png', ['alt' => 'Alternative text', 'class' => 'brand-logo'])
```

Fourth parameter is for including asset from secure location of your website, e.g. `https://...`. Its `false` by default.

And lastly, please notice that package throws Not Found Exception when asset doesn't exist.

## Requirements

- PHP >= 5.4
- Laravel >= 4.2

## Installation

- The **Imgjss** package can be installed via [Composer](http://getcomposer.org) by requiring the
`elvendor/imgjss` in your `composer.json` file:

```json
{
    "require": {
        "laravel/framework": "4.2.*",
        "elvendor/imgjss": "0.*"
    },
    "minimum-stability": "dev"
}
```
- Add Service Provider to providers list:
```php
'providers' => [
  // ...
  'Elvendor\Imgjss\ImgjssServiceProvider',
],
```
- If you plan to change package defaults, you can also publish config file:
```php
php artisan config:publish elvendor/imgjss
```
	
## Roadmap
- Avoid images extensions by looking for a file on given path (maybe not good idea)
- Setting default js, css, img paths
- Cover with tests

## Licence

[Imgjss](https://github.com/elvendor/imgjss) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
