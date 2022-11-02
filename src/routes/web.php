<?php

use Illuminate\Support\Facades\Route;

Route::get('/facebook-poster/authenticate-facebook-application', [SocialMediaAuthController::class, 'authenticate_facebook_application']);
Route::get('/linkedin-poster/authenticate-linkedin-application', [SocialMediaAuthController::class, 'authenticate_linkedin_application']);
Route::get('/facebook-poster/callback', [SocialMediaAuthController::class, 'handleProviderCallback_facebook_poster']);
Route::get('/linkedin-callback-poster', [SocialMediaAuthController::class, 'handleProviderCallback_linkedin_poster']);
