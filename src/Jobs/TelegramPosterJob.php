<?php

namespace SocialMedia\Poster\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use SocialMedia\Poster\Notifications\TelegramNotification;

class TelegramPosterJob extends SocialMediaPosterJob
{
    public function handle()
    {
        Notification::route('telegram', 'nafezly')
            ->notify(
                new TelegramNotification(
                    $this->content,
                    $this->image,
                    'nafezly',
                    true
                )
            );
    }
}
