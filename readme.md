[![Latest Version on Packagist](https://img.shields.io/packagist/v/mmaktaa1995/social-media-poster.svg?style=flat-square)](https://packagist.org/packages/mmaktaa1995/social-media-poster)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mmaktaa1995/social-media-poster/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/mmaktaa1995/social-media-poster/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mmaktaa1995/social-media-poster/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/mmaktaa1995/social-media-poster/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/mmaktaa1995/social-media-poster.svg?style=flat-square)](https://packagist.org/packages/mmaktaa1995/social-media-poster)

<p align="center"><img src="/art/social-media-poster-new.png" alt="Social Media Poster"></p>

# Push To Social [ Facebook , Twitter , Telegram , Linkedin ]
<!--/delete-->

Via this package you can push notifications to [ Facebook , Twitter , Telegram , Linkedin ] with a few params 
- content 
- image (optional)
- link (optional)
- 
<!--/delete-->

## Installation

You can install the package via composer:

```bash
composer require mmaktaa1995/social-media-poster
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag=":social-media-poster-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="social-media-poster-config"
```

This is the contents of the published config file:

```php
return [
  'platforms' => '*',

    'available-platforms' => [
        'facebook',
        'twitter',
        'telegram',
        'linkedin',
    ],

    'default_image' => 'https://nafezly.com/site_images/title.png?v=1',

    'binded-class' => '\App\Http\Controllers\SocialMediaAuthController',

    'redirect-url' => '/authentication-successfully',
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="social-media-poster-views"
```

# Usage
```php
// Use case 1
$socialMedia = new SocialMedia(['facebook', 'twitter']);
$socialMedia
    ->setContent('')
    ->setImage('')
    ->setLink('');

// Use case 2
$socialMedia = new SocialMedia();

$socialMedia->toFacebook()
    ->toLinkedin()
    ->toTelegram();

// Use case 3
$socialMedia->facebook()
    ->linkedin()
    ->telegram();

( new SocialHelper(
    ['facebook','twitter','telegram','linkedin'],
    "Hello I'm here, This Message From Push to social",
    'https://nafezly.com/site_images/title.png',
    'https://nafezly.com/'
))->publish();
```

# Authorize Facebook And Linkedin 

# Seed Database

- facebook

```json
{
   "FB_ACCESS_TOKEN":"",
   "APP_ID":"",
   "CLIENT_SECRET":"",
   "PAGE_ID":"",
   "REDIRECT_URL":"",
   "PAGE_ACCESS_TOKEN":""
}
```
- twitter

```json
{
   "API_KEY":"",
   "API_SECRET_KEY":"",
   "BEARER_TOKEN":"",
   "ACCESS_TOKEN":"",
   "ACCESS_TOKEN_SECRET":""
}
```
- linkedin

```json
{
   "CLIENT_ID":"",
   "CLIENT_SECRET":"",
   "REDIRECT_URL":"",
   "SCOPES":"r_emailaddress,r_basicprofile,w_member_social,w_organization_social,rw_organization_admin,rw_ads",
   "CODE":"",
   "ACCESS_TOKEN":"",
   "REFRESH_ACCESS_TOKEN":"",
   "ACCESS_TOKEN_EXPIRATION_DATE":"",
   "PAGE_ID":""
}
```
- telegram

```json
{
   "TELEGRAM_BOT_TOKEN":""
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
