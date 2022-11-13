<?php

namespace SocialMedia\Poster\Jobs;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterPosterJob extends SocialMediaPosterJob
{
    public function __construct($settings = [], $content = '', $image = null, $link = null)
    {
        $this->content = $content;
        $this->image = $image;
        $this->link = $link;
        $this->socialMediaSettings = $settings;

        if ($this->link != null)
        {
            $this->content = mb_strimwidth($this->content, 0, /*278 - strlen($this->link)*/ 257, '...') . "\n" . $this->link;
        }
        else
        {
            $this->content = mb_strimwidth($this->content, 0, 278, '...');
        }

        if ($this->image == 'NO')
        {
            $this->image = null;
        }
        elseif ($this->image == 'DEFAULT')
        {
            $this->image = 'https://nafezly.com/site_images/title.png?v=1';
        }
    }

    public function handle()
    {
        $API_KEY = $this->socialMediaSettings['API_KEY'];
        $API_SECRET_KEY = $this->socialMediaSettings['API_SECRET_KEY'];
//        $BEARER_TOKEN = $this->socialMediaSettings['BEARER_TOKEN'];
        $ACCESS_TOKEN = $this->socialMediaSettings['ACCESS_TOKEN'];
        $ACCESS_TOKEN_SECRET = $this->socialMediaSettings['ACCESS_TOKEN_SECRET'];

        $twitter = new TwitterOAuth($API_KEY, $API_SECRET_KEY, $ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);
        $twitter->get('account/verify_credentials');

        if ($this->image != null)
        {
            try
            {
                $uniqid = uniqid();
//                Image::make($this->image)->save(public_path('social_images/' . $uniqid . '.jpg'));
                $imageMedia = $twitter->upload('media/upload', ['media' => public_path('social_images/' . $uniqid . '.jpg')]);
                $parameters = ['status' => $this->content, 'media_ids' => $imageMedia->media_id_string];
            }
            catch (\Exception $e)
            {
                $parameters = ['status' => $this->content];
            }
        }
        else
        {
            $parameters = ['status' => $this->content];
        }
        $statuses = $twitter->post('statuses/update', $parameters);
        dump($statuses);
    }
}
