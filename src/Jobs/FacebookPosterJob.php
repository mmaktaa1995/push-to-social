<?php

namespace SocialMedia\Poster\Jobs;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookPosterJob extends SocialMediaPosterJob
{
    public function handle()
    {
        $APP_ID = $this->socialMediaSettings['APP_ID'];
        $CLIENT_SECRET = $this->socialMediaSettings['CLIENT_SECRET'];
        $PAGE_ID = $this->socialMediaSettings['PAGE_ID'];
//        $FB_TOKEN = $this->socialMediaSettings->facebook['FB_ACCESS_TOKEN'];
        $PAGE_ACCESS_TOKEN = $this->socialMediaSettings['PAGE_ACCESS_TOKEN'];

        $fb = new \Facebook\Facebook([
            'app_id'                => $APP_ID,
            'app_secret'            => $CLIENT_SECRET,
            'default_graph_version' => 'v2.10',
            //'default_access_token' => '61cdfc3a23278d3765190014a091b82a', // optional
        ]);

        try
        {
            // Returns a `FacebookFacebookResponse` object
            if ($this->image == null)
            {
                $response = $fb->post(
                    '/' . $PAGE_ID . '/feed',
                    [
                        'message'   => $this->content,
                        'link'      => $this->link,
                        'published' => 'true',
                    ],
                    $PAGE_ACCESS_TOKEN
                );
            }
            else
            {
                $response = $fb->post(
                    '/' . $PAGE_ID . '/photos',
                    [
                        'url'       => $this->image,
                        'caption'   => $this->content,
                        'published' => 'true',
                    ],
                    $PAGE_ACCESS_TOKEN
                );
            }
        }
        catch (FacebookResponseException $e)
        {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }
        catch (FacebookSDKException $e)
        {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();

        return dump($graphNode);
        //dd($graphNode);
    }
}
