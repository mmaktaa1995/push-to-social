<?php

namespace SocialMedia\Poster\Tests;

use Illuminate\Notifications\Notifiable as NotifiableTrait;
use Illuminate\Support\Str;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForTelegdram(): string|array
    {
        return Str::random();
    }

    public function getKey(): int
    {
        return 1;
    }
}
