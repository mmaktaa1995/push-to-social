<?php

namespace Spatie\MediaLibrary;

use Illuminate\Support\ServiceProvider;
use SocialMedia\Poster\SocialMedia;

class SocialMediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishables();

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../config/social-media-poster.php' => config_path('social-media-poster.php'),
        ], 'config');

        if (! class_exists('CreateSocialMediaSettingsTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_social_media_settings_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_social_media_settings_table.php'),
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/social-media-poster.php', 'social-media-poster');

        $this->app->singleton(SocialMedia::class, function () {
            $platforms = config('social-media-poster.platforms');
            if ($platforms == '*') {
                $platforms = $this->getAvailablePlatforms();
            }

            return new SocialMedia(new $platforms());
        });
    }

    private function getAvailablePlatforms()
    {
        return config('social-media-poster.available-platforms');
    }
}
