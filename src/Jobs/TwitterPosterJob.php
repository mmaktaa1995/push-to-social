<?php

namespace SocialMedia\Poster\Jobs;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TwitterPosterJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 2;
    public $timeout = 10;
    public $content;
    public $image;
    public $link;
    public $_SOCIAL_MEDIA_SETTINGS;

    public function __construct($content = [], $image = null, $link = null)
    {
        $this->content = $content;
        $this->image = $image;
        $this->link = $link;
        $this->_SOCIAL_MEDIA_SETTINGS = \App\SocialMediaSetting::first();



        if ($this->link != null) {
            $this->content = mb_strimwidth(implode("\n", $this->content), 0, /*278 - strlen($this->link)*/ 257, "...") . "\n".$this->link;
        } else {
            $this->content = mb_strimwidth(implode("\n", $this->content), 0, 278, "...");
        }


        if ($this->image == "NO") {
            $this->image = null;
        } elseif ($this->image == "DEFAULT") {
            $this->image = "https://nafezly.com/site_images/title.png?v=1";
        }
    }

    public function handle()
    {
        $API_KEY = json_decode($this->_SOCIAL_MEDIA_SETTINGS->twitter, true)['API_KEY'];
        $API_SECRET_KEY = json_decode($this->_SOCIAL_MEDIA_SETTINGS->twitter, true)['API_SECRET_KEY'];
        $BEARER_TOKEN = json_decode($this->_SOCIAL_MEDIA_SETTINGS->twitter, true)['BEARER_TOKEN'];
        $ACCESS_TOKEN = json_decode($this->_SOCIAL_MEDIA_SETTINGS->twitter, true)['ACCESS_TOKEN'];
        $ACCESS_TOKEN_SECRET = json_decode($this->_SOCIAL_MEDIA_SETTINGS->twitter, true)['ACCESS_TOKEN_SECRET'];

        $twitter = new TwitterOAuth($API_KEY, $API_SECRET_KEY, $ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);
        $contentx = $twitter->get("account/verify_credentials");

        if ($this->image != null) {
            try {
                $uniqid = uniqid();
                \Image::make($this->image)->save(public_path('social_images/'.$uniqid.'.jpg'));
                $imageMedia = $twitter->upload('media/upload', ['media' => public_path('social_images/'.$uniqid.'.jpg')]);
                $parameters = ["status" => $this->content, "media_ids" => $imageMedia->media_id_string];
            } catch(\Exception $e) {
                $parameters = ["status" => $this->content];
            }
        } else {
            $parameters = ["status" => $this->content];
        }
        $statuses = $twitter->post("statuses/update", $parameters);
        dump($statuses);
    }
}
