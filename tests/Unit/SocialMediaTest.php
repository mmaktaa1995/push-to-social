<?php

namespace SocialMedia\Poster\Tests\Unit;

use Illuminate\Support\Facades\Bus;
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
        $item1 = SocialMediaSetting::query()->create([
            'facebook' => [
                'app_id' => 12312321,
            ],
        ]);

        $collection->push($item1);
        $item2 = SocialMediaSetting::query()->create([
            'facebook' => [
                'app_id' => '2342fds',
            ],
        ]);

        $this->assertDatabaseHas('social_media_settings', ['facebook' => json_encode([
            'app_id' => 12312321,
        ])]);

        $this->assertContains($item1, $collection);
        $this->assertNotContains($item2, $collection);
        $this->assertEquals('2342fds', $item2->facebook['app_id']);
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
                'app_id' => '2342fds',
            ],
        ]);

        $socialMedia = new SocialMedia([], ['test facebook']);
        $socialMedia->toFacebook();

        Bus::assertDispatched(FacebookPosterJob::class);
        $this->assertTrue($socialMedia->content == 'test');
    }
}
