<?php

namespace SocialMedia\Poster\Tests\Feature;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use SocialMedia\Poster\Exceptions\MissingSocialMediaSettingsException;
use SocialMedia\Poster\Jobs\TelegramPosterJob;
use SocialMedia\Poster\Models\SocialMediaSetting;
use SocialMedia\Poster\Notifications\TelegramNotification;
use SocialMedia\Poster\SocialMedia;
use SocialMedia\Poster\Tests\Notifiable;
use SocialMedia\Poster\Tests\TestCase;

class TelegramSocialPosterTest extends TestCase
{
    /**
     * @test
     *
     * @testdox Publish to telegram
     */
    public function test_user_can_post_to_telegram()
    {
        $this->busFake();
        $this->notificationFake();
        $settings = SocialMediaSetting::query()->create([
            'telegram' => [
                'app_id' => Str::random(),
            ],
        ]);

        $socialMedia = new SocialMedia([], 'test telegram');
        $socialMedia->toTelegram();

        Bus::assertDispatched(TelegramPosterJob::class);

        Notification::send([new Notifiable()], new TelegramNotification(['']));
        Notification::assertSentTo(new Notifiable(), TelegramNotification::class);
    }

    /**
     * @test
     *
     * @testdox Telegram config missing from database
     */
    public function test_user_can_not_post_to_telegram_due_to_missing_config()
    {
        $this->expectException(MissingSocialMediaSettingsException::class);
        $this->busFake();
        SocialMediaSetting::query()->create([]);
        $socialMedia = new SocialMedia([], 'test telegram');
        $socialMedia->toTelegram();

        Bus::assertDispatched(TelegramPosterJob::class);
        $this->assertEquals('test telegram', $socialMedia->content);
    }
}
