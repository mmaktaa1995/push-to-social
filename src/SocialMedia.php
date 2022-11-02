<?php

namespace Social\PushToSocial;

use App\Jobs\{FacebookPosterJob, LinkedInPoster, TelegramPosterJob, TwitterPosterJob};
use App\SocialMediaSetting;

class SocialHelper
{
    /**
     * @var \App\SocialMediaSetting
     */
    private $_SOCIAL_MEDIA_SETTINGS;

    public function __construct(public $platforms = null, public $content = null, public $image = "DEFAULT", public $link = null)
    {
        //$this->inline_content=implode( " ", $content );
        $this->_SOCIAL_MEDIA_SETTINGS = SocialMediaSetting::first();
    }

    public function push()
    {
        if (in_array("facebook", $this->platforms)) {
            $this->toFacebook();
        }
        if (in_array("twitter", $this->platforms)) {
            $this->toTwitter();
        }
        if (in_array("linkedin", $this->platforms)) {
            $this->toLinkedin();
        }
        if (in_array("telegram", $this->platforms)) {
            $this->toTelegram();
        }
    }

    public function toFacebook()
    {
        FacebookPosterJob::dispatch($this->content, $this->image, $this->link);
    }

    public function toTwitter()
    {
        TwitterPosterJob::dispatch($this->content, $this->image, $this->link);
    }

    public function toLinkedin()
    {
        LinkedInPoster::dispatch($this->content, $this->image, $this->link);
    }

    public function toTelegram()
    {
        TelegramPosterJob::dispatch($this->content, $this->image, $this->link);
    }
}
