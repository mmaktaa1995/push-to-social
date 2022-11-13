<?php

namespace SocialMedia\Poster\Http\Controllers;

use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SocialMedia\Poster\Models\SocialMediaSetting;

class SocialMediaAuthController
{
    /*public function __construct()
    {
        $this->middleware('IsAdmin');
    }*/

    public function __invoke()
    {
        return view('social-media-poster::redirect');
    }

    public $socialMediaSettings;

    /**
     * @throws \Facebook\Exceptions\FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function handleProviderCallbackFacebookPoster()
    {
        session_start();

        $socialMediaSettings = SocialMediaSetting::query()->first();
        $socialMediaSettingsFacebook = $socialMediaSettings->facebook;
        $APP_ID = $socialMediaSettingsFacebook['APP_ID'];
        $APP_SECRET = $socialMediaSettingsFacebook['APP_SECRET'];

        $fb = new \Facebook\Facebook([
            'app_id'                => $APP_ID,
            'app_secret'            => $APP_SECRET,
            'default_graph_version' => 'v2.10',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try
        {
            $accessToken = $helper->getAccessToken();
        }
        catch (FacebookResponseException $facebookResponseException)
        {
            // When Graph returns an error
            throw $facebookResponseException;
        }
        catch (FacebookSDKException $facebookSDKException)
        {
            // When validation fails or other local issues
            throw $facebookSDKException;
        }

        if (!isset($accessToken))
        {
            if ($helper->getError())
            {
                header('HTTP/1.0 401 Unauthorized');
                echo 'Error: ' . $helper->getError() . "\n";
                echo 'Error Code: ' . $helper->getErrorCode() . "\n";
                echo 'Error Reason: ' . $helper->getErrorReason() . "\n";
                echo 'Error Description: ' . $helper->getErrorDescription() . "\n";
            }
            else
            {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($APP_ID);

        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived())
        {
            // Exchanges a short-lived access token for a long-lived one
            try
            {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            }
            catch (FacebookSDKException $e)
            {
                throw $e;
            }
        }
        session(['fb_access_token' => (string)$accessToken]);

        $pageAccessToken = Http::get('https://graph.facebook.com/' . ($socialMediaSettingsFacebook['PAGE_ID'] ?? $fb->getPageTabHelper()->getPageId()) . '?fields=access_token&access_token=' . $accessToken->getValue())->json();

        $socialMediaSettingsFacebook['FB_ACCESS_TOKEN'] = $accessToken->getValue();
        $socialMediaSettingsFacebook['PAGE_ACCESS_TOKEN'] = $pageAccessToken['access_token'];
        $socialMediaSettings->update(['facebook' => $socialMediaSettingsFacebook]);

        return redirect(config('social-media-poster.redirect-url'))->with('data', ['alert' => 'Now (Publishing) Is Authenticated To Post Posts On Facebook', 'alert-type' => 'success']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallbackLinkedinPoster(Request $request)
    {
        $socialMediaSettings = SocialMediaSetting::query()->first();
        $socialMediaSettingsLinkedin = $socialMediaSettings->linkedin;
        $socialMediaSettingsLinkedin['CODE'] = $request->code;

        $CLIENT_ID = $socialMediaSettingsLinkedin['CLIENT_ID'];
        $CLIENT_SECRET = $socialMediaSettingsLinkedin['CLIENT_SECRET'];
        $REDIRECT_URL = $socialMediaSettingsLinkedin['REDIRECT_URL'];
        $CODE = $socialMediaSettingsLinkedin['CODE'];

        $res = Http::withHeaders(['content-type' => 'application/x-www-form-urlencoded'])->post(
            "https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code=$CODE&redirect_uri=$REDIRECT_URL&client_id=$CLIENT_ID&client_secret=$CLIENT_SECRET"
        );

        $socialMediaSettingsLinkedin['ACCESS_TOKEN'] = $res->json()['access_token'];
        $socialMediaSettingsLinkedin['REFRESH_ACCESS_TOKEN'] = $res->json()['refresh_token'];
        $socialMediaSettingsLinkedin['ACCESS_TOKEN_EXPIRATION_DATE'] = Carbon::parse(now())->addDays(60);
        $socialMediaSettings->update(['linkedin' => $socialMediaSettingsLinkedin]);

        return redirect(config('social-media-poster.redirect-url'))->with('data', ['alert' => 'Now (Nafezly Publishing) Is Authenticated To Post Posts On LinkedIn', 'alert-type' => 'success']);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function authenticateFacebookApplication()
    {
        session_start();
        $socialMediaSettings = SocialMediaSetting::query()->first();
        $APP_ID = $socialMediaSettings->facebook['APP_ID'];
        $APP_SECRET = $socialMediaSettings->facebook['APP_SECRET'];
        $REDIRECT_URL = $socialMediaSettings->facebook['REDIRECT_URL'];

        $fb = new \Facebook\Facebook([
            'app_id'                => $APP_ID,
            'app_secret'            => $APP_SECRET,
            'default_graph_version' => 'v2.10',
        ]);
        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['pages_manage_posts', 'pages_read_user_content', 'email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($REDIRECT_URL, $permissions);

        return redirect($loginUrl);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticateLinkedinApplication()
    {
        $socialMediaSettings = SocialMediaSetting::query()->first();
        $CLIENT_ID = $socialMediaSettings->linkedin['CLIENT_ID'];
//        $CLIENT_SECRET = $this->socialMediaSettings->linkedin['CLIENT_SECRET'];
        $REDIRECT_URL = $socialMediaSettings->linkedin['REDIRECT_URL'];
        $SCOPES = $socialMediaSettings->linkedin['SCOPES'];

        return redirect('https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=' . $CLIENT_ID . '&redirect_uri=' . $REDIRECT_URL . '&scope=' . $SCOPES . ';');
    }
}
