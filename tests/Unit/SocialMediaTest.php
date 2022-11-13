<?php

namespace SocialMedia\Poster\Tests\Unit;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SocialMedia\Poster\SocialMedia;
use SocialMedia\Poster\Tests\TestCase;

class SocialMediaTest extends TestCase
{
    /**
     * @test
     */
    public function test_db_has_our_needed_config()
    {
        $appId = null;
        $this->createPlatformsRecord($appId);

        $this->assertDatabaseHas(
            'social_media_settings',
            [
                'facebook' => json_encode(['APP_ID' => $appId]),
            ]
        );

        $this->assertDatabaseHas(
            'social_media_settings',
            [
                'twitter' => json_encode(['APP_ID' => $appId]),
            ]
        );

        $this->assertDatabaseHas(
            'social_media_settings',
            [
                'linkedin' => json_encode(['APP_ID' => $appId]),
            ]
        );
    }

    /**
     * @test
     *
     * @testdox Social media settings missing from database
     */
    public function test_user_can_not_publish_due_to_missing_config()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->busFake();

        $socialMedia = new SocialMedia('*', ['']);
        $socialMedia->publish();
    }

    /**
     * @test
     */
    public function test_social_media_class_has_required_attributes()
    {
        $this->assertClassHasAttribute('platforms', SocialMedia::class);
        $this->assertClassHasAttribute('socialMediaSettings', SocialMedia::class);
        $this->assertClassHasAttribute('content', SocialMedia::class);
    }

    /**
     * @test
     */
    public function test_social_media_class_has_required_methods()
    {
        $class = new \ReflectionClass(SocialMedia::class);

        $this->assertTrue($class->hasMethod('toFacebook'));
        $this->assertTrue($class->hasMethod('toLinkedin'));
        $this->assertTrue($class->hasMethod('toTwitter'));
        $this->assertTrue($class->hasMethod('toTelegram'));
    }
}
