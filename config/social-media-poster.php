<?php

return [
    'platforms' => '*',

    'available-platforms' => [
        'facebook',
        'twitter',
        'telegram',
        'linkedin',
        'whatsapp',
        'google',
    ],

    'default_image' => 'https://nafezly.com/site_images/title.png?v=1',

    'binded-class' => '\App\Http\Controllers\SocialMediaAuthController',

    'redirect-url' => '/'
];
