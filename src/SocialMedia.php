<?php

namespace SocialMedia\Poster;

use SocialMedia\Poster\Jobs\{FacebookPosterJob, LinkedInPoster, TelegramPosterJob, TwitterPosterJob};
use SocialMedia\Poster\Models\SocialMediaSetting;

class SocialMedia extends SocialMediaAbstract
{
    public function publish()
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
        $settings = $this->socialMediaSettings->facebook;
        throw_if(! count($settings), new \MissingSocialMediaSettingsException("Settings for {Facebook} provider is missing!"));
        FacebookPosterJob::dispatch($settings, $this->content, $this->image, $this->link);

        return $this;
    }

    public function toTwitter()
    {
        $settings = $this->socialMediaSettings->twitter;
        throw_if(! count($settings), new \MissingSocialMediaSettingsException("Settings for {Twitter} provider is missing!"));

        TwitterPosterJob::dispatch($settings, $this->content, $this->image, $this->link);

        return $this;
    }

    public function toLinkedin()
    {
        $settings = $this->socialMediaSettings->linkedin;
        throw_if(! count($settings), new \MissingSocialMediaSettingsException("Settings for {Linkedin} provider is missing!"));
        LinkedInPoster::dispatch($settings, $this->content, $this->image, $this->link);

        return $this;
    }

    public function toTelegram()
    {
        $settings = $this->socialMediaSettings->telegram;
        throw_if(! count($settings), new \MissingSocialMediaSettingsException("Settings for {Telegram} provider is missing!"));
        TelegramPosterJob::dispatch($settings, $this->content, $this->image, $this->link);

        return $this;
    }

    protected function getSocialMediaSettings()
    {
        return SocialMediaSetting::query()->first();
    }

    public function setContent($content = '')
    {
        $this->content = $content;
        return $content;
    }

    public function setLink($link = '')
    {
        $this->link = $link;
        return $link;
    }

    public function setImage($image = 'DEFAULT')
    {
        $this->image = $image;
        return $image;
    }
}
