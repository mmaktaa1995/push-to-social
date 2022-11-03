<?php

namespace SocialMedia\Poster\Jobs;

class LinkedInPoster extends SocialMediaPosterJob
{
    public function handle()
    {
        $CLIENT_ID = json_decode($this->socialMediaSettings->linkedin, true)['CLIENT_ID'];
        $CLIENT_SECRET = json_decode($this->socialMediaSettings->linkedin, true)['CLIENT_SECRET'];
        $ACCESS_TOKEN = json_decode($this->socialMediaSettings->linkedin, true)['ACCESS_TOKEN'];
        $PAGE_ID = json_decode($this->socialMediaSettings->linkedin, true)['PAGE_ID'];

        $data = [
            'author'          => 'urn:li:organization:' . $PAGE_ID,
            'lifecycleState'  => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $this->content,
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        if ($this->link != null)
        {
            $data = [
                'author'          => 'urn:li:organization:' . $PAGE_ID,
                'lifecycleState'  => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => [
                            'text' => $this->content,
                        ],
                        'shareMediaCategory' => 'ARTICLE',
                        'media'              => [
                            [
                                'status'      => 'READY',
                                'originalUrl' => $this->link,
                            ],
                        ],
                    ],
                ],
                'visibility' => [
                    'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
                ],
            ];
        }
        if ($this->image != null)
        {
            //register image
            $image_register_data = [
                'registerUploadRequest' => [
                    'owner'   => 'urn:li:organization:' . $PAGE_ID,
                    'recipes' => [
                        'urn:li:digitalmediaRecipe:feedshare-image',
                    ],
                    'serviceRelationships' => [
                        [
                            'identifier'       => 'urn:li:userGeneratedContent',
                            'relationshipType' => 'OWNER',
                        ],
                    ],
                    'supportedUploadMechanism' => [
                        'SYNCHRONOUS_UPLOAD',
                    ],
                ],
            ];

            $image_register_res = \Http::withHeaders(['Authorization' => 'Bearer ' . $ACCESS_TOKEN, 'X-Restli-Protocol-Version' => '2.0.0', 'Content-Type' => 'application/json'])->post('https://api.linkedin.com/v2/assets?action=registerUpload', $image_register_data);
            $upload_url = $image_register_res->json()['value']['uploadMechanism']['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
            $upload = \Http::withBody(
                file_get_contents($this->image),
                'image/jpeg'
            )->withHeaders(['Authorization' => "Bearer $ACCESS_TOKEN", 'X-Restli-Protocol-Version' => '2.0.0'])->put($upload_url);
            $check = \Http::withHeaders(['Authorization' => 'Bearer ' . $ACCESS_TOKEN, 'X-Restli-Protocol-Version' => '2.0.0'])->get('https://api.linkedin.com/v2/assets/' . str_replace('urn:li:digitalmediaAsset:', '', $image_register_res->json()['value']['asset']))->json();
            $data = [
                'author'          => 'urn:li:organization:' . $PAGE_ID,
                'lifecycleState'  => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => [
                            'text' => $this->content,
                        ],
                        'shareMediaCategory' => 'IMAGE',
                        'media'              => [
                            [
                                'media'  => $image_register_res->json()['value']['asset'],
                                'status' => 'READY',
                            ],
                        ],
                    ],
                ],
                'visibility' => [
                    'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
                ],
            ];
        }

        $res = \Http::withHeaders(['Authorization' => 'Bearer ' . $ACCESS_TOKEN, 'X-Restli-Protocol-Version' => '2.0.0', 'Content-Type' => 'application/json'])->post('https://api.linkedin.com/v2/ugcPosts', $data);

        dd($res->json());
    }
}
