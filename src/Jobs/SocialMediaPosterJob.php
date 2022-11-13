<?php

namespace SocialMedia\Poster\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class SocialMediaPosterJob implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    use SerializesModels;

    public $tries = 2;

    public $timeout = 10;

    /**
     * @var array
     */
    public $socialMediaSettings;

    public function __construct(public $settings = [], public $content = '', public $image = null, public $link = null)
    {
        $this->socialMediaSettings = $settings;
        if ($this->link != null)
        {
            str($this->content)->append("\n{$this->link}");
        }

        if ($this->image == 'NO')
        {
            $this->image = null;
        }
        elseif ($this->image == 'DEFAULT')
        {
            $this->image = config('social-media-poster.default_image');
        }

    }
}
