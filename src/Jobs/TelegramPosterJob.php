<?php

namespace SocialMedia\Poster\Jobs;

use Illuminate\Support\Facades\Notification;
use SocialMedia\Poster\Notifications\TelegramNotification;

class TelegramPosterJob extends SocialMediaPosterJob
{
    public function handle()
    {
        Notification::route('telegram', 'nafezly')
            ->notifyNow(
                new TelegramNotification(
                    $this->content,
                    $this->image,
                    'nafezly',
                    true
                )
            );
    }
}
