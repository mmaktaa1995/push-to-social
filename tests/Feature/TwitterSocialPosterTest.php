<?php

namespace SocialMedia\Poster\Tests\Feature;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use SocialMedia\Poster\Exceptions\MissingSocialMediaSettingsException;
use SocialMedia\Poster\Jobs\TwitterPosterJob;
use SocialMedia\Poster\Models\SocialMediaSetting;
use SocialMedia\Poster\SocialMedia;
use SocialMedia\Poster\Tests\TestCase;

class TwitterSocialPosterTest extends TestCase
{
    /**
     * @test
     *
     * @testdox Publish to twitter
     */
    public function test_user_can_post_to_twitter()
    {
        $this->busFake();
        SocialMediaSetting::query()->create([
            'twitter' => [
                'app_id' => Str::random(),
            ],
        ]);

        $socialMedia = new SocialMedia([], 'test twitter');
        $socialMedia->toTwitter();

        Bus::assertDispatched(TwitterPosterJob::class);
        $this->assertEquals('test twitter', $socialMedia->content);
    }

    /**
     * @test
     *
     * @testdox Twitter config missing from database
     */
    public function test_user_can_not_post_to_twitter_due_to_missing_config()
    {
        $this->expectException(MissingSocialMediaSettingsException::class);
        $this->busFake();
        SocialMediaSetting::query()->create([]);
        $socialMedia = new SocialMedia([], 'test twitter');
        $socialMedia->toTwitter();

        Bus::assertDispatched(TwitterPosterJob::class);
        $this->assertEquals('test twitter', $socialMedia->content);
    }
}
