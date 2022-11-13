<?php

namespace SocialMedia\Poster\Tests\Unit;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use SocialMedia\Poster\Jobs\FacebookPosterJob;
use SocialMedia\Poster\Models\SocialMediaSetting;
use SocialMedia\Poster\SocialMedia;
use SocialMedia\Poster\Tests\TestCase;

class SocialMediaTest extends TestCase
{
    /**
     * @test
     */
    public function test_db_has_facebook_config()
    {
        $collection = collect();
        $appId1 = Str::random();
        $appId2 = Str::random();
        $item1 = SocialMediaSetting::query()->create([
            'facebook' => [
                'app_id' => $appId1
            ]
        ]);

        $collection->push($item1);
        $item2 = SocialMediaSetting::query()->create([
            'facebook' => [
                'app_id' => $appId2
            ]
        ]);

        $this->assertDatabaseHas('social_media_settings', ['facebook' => json_encode([
            'app_id' => $appId1
        ])]);

        $this->assertContains($item1, $collection);
        $this->assertNotContains($item2, $collection);
        $this->assertEquals($appId2, $item2->facebook['app_id']);
    }

    /**
     * @test
     *
     * @testdox Post to facebook
     */
    public function test_it_can_post_to_facebook()
    {
        $this->busFake();
        SocialMediaSetting::query()->create([
            'facebook' => [
                'app_id' => Str::random()
            ]
        ]);

        $socialMedia = new SocialMedia([], ['test facebook']);
        $socialMedia->toFacebook();

        Bus::assertDispatched(FacebookPosterJob::class);
        $this->assertTrue(Str::contains('test facebook', $socialMedia->content));
    }
}
