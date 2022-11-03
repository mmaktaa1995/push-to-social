<p align="center"><img src="/art/social-media-poster-new.png" alt="Social Media Poster"></p>


# Push To Social [ Facebook , Twitter , Telegram , Linkedin ]

via this package you can push notifications to [ Facebook , Twitter , Telegram , Linkedin ] 
- content 
- image ( Optional )
- link ( Optional )

```php
(new SocialHelper($platforms=[],$content=[],$image='',$link=''))->push()
```
## How To Push

```php
( new SocialHelper(
    ['facebook','twitter','telegram','linkedin'],
    ['Hello', 'Iam here','Message From Push to social'],
    'https://nafezly.com/site_images/title.png',
    'https://nafezly.com/'
) )->push();
```

# You have to install

```console
composer require abraham/twitteroauth
composer require facebook/graph-sdk
composer require laravel-notification-channels/telegram
```

# Migrations for laravel

```php
Schema::create('social_media_settings', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->json('facebook')->nullable();
    $table->json('twitter')->nullable();
    $table->json('telegram')->nullable();
    $table->json('linkedin')->nullable();
    $table->json('whatsapp')->nullable();
    $table->json('google')->nullable();

    $table->timestamps();
});
```
- Create Jobs folder inside app folder
- Move all Jobs in the repo to Jobs Folder
- Create Notifications Folder inside app folder For telegram
- Move TeleNotification To Notifications folder
- Move Routes , Models and Controllers To Your Project


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
