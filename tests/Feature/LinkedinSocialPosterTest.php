<?php

namespace SocialMedia\Poster\Tests\Feature;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use SocialMedia\Poster\Exceptions\MissingSocialMediaSettingsException;
use SocialMedia\Poster\Jobs\LinkedInPosterJob;
use SocialMedia\Poster\Models\SocialMediaSetting;
use SocialMedia\Poster\SocialMedia;
use SocialMedia\Poster\Tests\TestCase;

class LinkedinSocialPosterTest extends TestCase
{
    /**
     * @test
     *
     * @testdox Publish to LinkedIn
     */
    public function test_user_can_post_to_linkedin()
    {
        $this->busFake();
        SocialMediaSetting::query()->create([
            'linkedin' => [
                'app_id' => Str::random(),
            ],
        ]);

        $socialMedia = new SocialMedia([], 'test linkedin');
        $socialMedia->toLinkedin();

        Bus::assertDispatched(LinkedInPosterJob::class);
        $this->assertEquals('test linkedin', $socialMedia->content);
    }

    /**
     * @test
     *
     * @testdox LinkedIn config missing from database
     */
    public function test_user_can_not_post_to_linkedin_due_to_missing_config()
    {
        $this->expectException(MissingSocialMediaSettingsException::class);
        $this->busFake();
        SocialMediaSetting::query()->create([]);
        $socialMedia = new SocialMedia([], 'test linkedin');
        $socialMedia->toLinkedin();

        Bus::assertDispatched(LinkedinPosterJob::class);
        $this->assertEquals('test linkedin', $socialMedia->content);
    }
}
