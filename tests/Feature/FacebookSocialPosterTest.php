<?php

namespace SocialMedia\Poster\Tests\Feature;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use SocialMedia\Poster\Exceptions\MissingSocialMediaSettingsException;
use SocialMedia\Poster\Jobs\FacebookPosterJob;
use SocialMedia\Poster\Models\SocialMediaSetting;
use SocialMedia\Poster\SocialMedia;
use SocialMedia\Poster\Tests\TestCase;

class FacebookSocialPosterTest extends TestCase
{
    /**
     * @test
     *
     * @testdox Publish to facebook
     */
    public function test_user_can_post_to_facebook()
    {
        $this->busFake();
        SocialMediaSetting::query()->create([
            'facebook' => [
                'app_id' => Str::random(),
            ],
        ]);

        $socialMedia = new SocialMedia([], 'test facebook');
        $socialMedia->toFacebook();

        Bus::assertDispatched(FacebookPosterJob::class);
        $this->assertEquals('test facebook', $socialMedia->content);
    }

    /**
     * @test
     *
     * @testdox Facebook config missing from database
     */
    public function test_user_can_not_post_to_facebook_due_to_missing_config()
    {
        $this->expectException(MissingSocialMediaSettingsException::class);
        $this->busFake();
        SocialMediaSetting::query()->create([]);
        $socialMedia = new SocialMedia([], 'test facebook');
        $socialMedia->toFacebook();

        Bus::assertDispatched(FacebookPosterJob::class);
        $this->assertEquals('test facebook', $socialMedia->content);
    }
}
