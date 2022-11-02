<?php

namespace SocialMedia\Poster\Jobs;

class FacebookPosterJob extends SocialMediaPosterJob
{
    public function handle()
    {
        $APP_ID = json_decode($this->socialMediaSettings->facebook, true)['APP_ID'];
        $CLIENT_SECRET = json_decode($this->socialMediaSettings->facebook, true)['CLIENT_SECRET'];
        $PAGE_ID = json_decode($this->socialMediaSettings->facebook, true)['PAGE_ID'];
        $FB_TOKEN = json_decode($this->socialMediaSettings->facebook, true)['FB_ACCESS_TOKEN'];
        $PAGE_ACCESS_TOKEN = json_decode($this->socialMediaSettings->facebook, true)['PAGE_ACCESS_TOKEN'];

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
        catch (FacebookExceptionsFacebookResponseException $e)
        {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }
        catch (FacebookExceptionsFacebookSDKException $e)
        {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();

        return dump($graphNode);
        //dd($graphNode);
    }
}
