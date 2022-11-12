<?php

namespace SocialMedia\Poster\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use SocialMedia\Poster\SocialMediaServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    protected static $migrations;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        UploadedFile::fake();
        $this->prepareMigration();
        self::$migrations->up();
    }

    private function prepareMigration()
    {
        self::$migrations = require __DIR__ . '/../database/migrations/create_social_media_settings_table.php.stub';
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [SocialMediaServiceProvider::class];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->getEnvironmentSetUp($app);
        self::$migrations->up();
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function busFake()
    {
        Bus::fake();
        return $this;
    }

    protected function notificationFake()
    {
        Notification::fake();
        return $this;
    }

    protected function eventFake()
    {
        Event::fake();
        return $this;
    }

    protected function cacheFake()
    {
        Cache::spy();
        return $this;
    }

    protected function mailFake()
    {
        Mail::fake();
        return $this;
    }

    protected function queueFake()
    {
        Queue::fake();
        return $this;
    }
}
