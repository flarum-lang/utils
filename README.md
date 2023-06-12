# Utilities for [Flarum](https://flarum.org/) Language Packs

[![Latest Stable Version](https://img.shields.io/packagist/v/flarum-lang/utils?color=success&label=stable)](https://packagist.org/packages/flarum-lang/utils) 
[![Latest Unstable Version](https://img.shields.io/packagist/v/flarum-lang/utils?include_prereleases&label=unstable)](https://packagist.org/packages/flarum-lang/utils) 
[![License](https://img.shields.io/packagist/l/flarum-lang/utils)](https://packagist.org/packages/flarum-lang/utils) 
[![Total Downloads](https://img.shields.io/packagist/dt/flarum-lang/utils)](https://packagist.org/packages/flarum-lang/utils/stats) 
[![Monthly Downloads](https://img.shields.io/packagist/dm/flarum-lang/utils)](https://packagist.org/packages/flarum-lang/utils/stats) 

This package provides useful utilities for Flarum Language Packs.


## Installation

You can install this package using [Composer](https://getcomposer.org/):

```console
composer require flarum-lang/utils
```


## Usage

### `LanguagePackWithVariants`

This is an extender dedicated for language packs with multiple variants of translations (for example formal and informal translations). 
Use it instead of `Flarum\Extend\LanguagePack` in `extend.php`:

```php
return [
    new FlarumLang\Utils\Extend\LanguagePackWithVariants([
        'label' => 'Variant',
        'variants' => [
          'informal' => 'Informal',
          'formal' => 'Formal',
        ],
        'defaultVariant' => 'informal',
    ]),
];
```


## Links

- [Packagist](https://packagist.org/packages/flarum-lang/utils)
- [GitHub](https://github.com/flarum-lang/utils)
- [Discuss](https://discuss.flarum.org/d/27519-the-flarum-language-project)
