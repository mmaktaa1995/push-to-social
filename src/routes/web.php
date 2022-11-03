<?php

use Illuminate\Support\Facades\Route;
use SocialMedia\Poster\Http\Controllers\SocialMediaAuthController;

Route::get('/facebook-poster/authenticate-facebook-application', [SocialMediaAuthController::class, 'authenticateFacebookApplication']);
Route::get('/linkedin-poster/authenticate-linkedin-application', [SocialMediaAuthController::class, 'authenticateLinkedinApplication']);
Route::get('/facebook-poster/callback', [SocialMediaAuthController::class, 'handleProviderCallbackFacebookPoster']);
Route::get('/linkedin-callback-poster', [SocialMediaAuthController::class, 'handleProviderCallbackLinkedinPoster']);
