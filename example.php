<?php

use SocialMedia\Poster\SocialMedia;

require "vendor/autoload.php";

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
