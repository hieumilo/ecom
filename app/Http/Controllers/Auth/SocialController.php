<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Social;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Hash;
use Validator;
use Carbon\Carbon;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Response as HttpResponse;
use Exception;

class SocialController extends Controller
{
    private function createSocialUser(array $profile, $provider)
    {
        if ($provider == 'facebook') {
            $provider_user_id = $profile['id'];
            $avatar = $profile['picture']['data']['url'];
        } else {
            $provider_user_id = $profile['sub'];
            $avatar = $profile['picture'];
            $profile['gender'] = 'male';
        }

        $social = Social::where('provider_user_id',$provider_user_id)
            ->where('provider',$provider)
            ->first();

        if ($social) {
            $user = User::where('id',$social->user_id)->first();
            $token = JWTAuth::fromUser($user);

            return Response()->json([
                'token' => $token,
            ], 200);
        } else {
            DB::beginTransaction();

            try {
                $user = new User;
                $user->name = $profile['name'];
                $user->email = $profile['email'];
                $user->avatar = $avatar;
                $user->gender = $profile['gender'] == 'male' ? true : false;
                $user->is_admin = false;
                $user->created_at = Carbon::now();
                $user->save();

                $social = new Social;
                $social->provider_user_id = $provider_user_id;
                $social->provider = $provider;
                $social->user_id = $user->id;
                $social->created_at = Carbon::now();
                $social->save();

                DB::commit();

                $token = JWTAuth::fromUser($user);

                return Response()->json([
                    'token' => $token,
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();

                return Response()->json([
                    'error' => $e->getMessage(),
                ], 442);
            }
        }
    }

    public function facebook(Request $request)
    {
        $client = new GuzzleHttp\Client();
        $params = [
            'code' => $request->code,
            'client_id' => $request->clientId,
            'redirect_uri' => $request->redirectUri,
            'client_secret' => config('services.FACEBOOK_SECRET'),
        ];

        $accessTokenResponse = $client->request('GET', config('services.url_facebook_token_access'), [
            'query' => $params,
        ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);

        $fields = 'id,email,first_name,last_name,link,name,gender,picture';
        $profileResponse = $client->request('GET', config('services.url_facebook_me'), [
            'query' => [
                'access_token' => $accessToken['access_token'],
                'fields' => $fields,
            ]
        ]);
        $profile = json_decode($profileResponse->getBody(), true);

        return $this->createSocialUser($profile, 'facebook');
    }

    public function google(Request $request)
    {
        $client = new GuzzleHttp\Client();
        $params = [
            'code' => $request->code,
            'client_id' => $request->clientId,
            'redirect_uri' => $request->redirectUri,
            'client_secret' => config('services.GOOGLE_SECRET'),
            'grant_type' => 'authorization_code',
        ];

        $accessTokenResponse = $client->request('POST', config('services.url_account_google_token'), [
            'form_params' => $params,
        ]);
        $accessToken = json_decode($accessTokenResponse->getBody(), true);

        $profileResponse = $client->request('GET', config('services.url_google_me'), [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken['access_token'],
            ]
        ]);
        $profile = json_decode($profileResponse->getBody(), true);

        return $this->createSocialUser($profile, 'google');
    }

    public function twitter(Request $request)
    {
        $stack = GuzzleHttp\HandlerStack::create();

        if (!$request->oauth_token || !$request->oauth_verifier) {
            $stack = GuzzleHttp\HandlerStack::create();
            $requestTokenOauth = new Oauth1([
                'consumer_key' => config('services.twitter_key'),
                'consumer_secret' => config('services.twitter_secret'),
                'callback' => $request->redirectUri,
                'token' => config('services.TWITTER_TOKEN'),
                'token_secret' => config('services.TWITTER_TOKEN_SECRET'),
            ]);
            $stack->push($requestTokenOauth);
            $client = new GuzzleHttp\Client([
                'handler' => $stack,
            ]);

            $requestTokenResponse = $client->request('POST', config('services.url_twitter_request_token'), [
                'auth' => 'oauth',
            ]);
            $oauthToken = [];
            parse_str($requestTokenResponse->getBody(), $oauthToken);

            return response()->json($oauthToken);
        } else {
            $accessTokenOauth = new Oauth1([
                'consumer_key' => config('services.TWITTER_KEY'),
                'consumer_secret' => config('services.TWITTER_SECRET'),
                'token' => $request->oauth_token,
                'verifier' => $request->oauth_verifier,
                'token_secret' => config('services.TWITTER_TOKEN_SECRET'),
            ]);
            $stack->push($accessTokenOauth);
            $client = new GuzzleHttp\Client([
                'handler' => $stack,
            ]);

            $accessTokenResponse = $client->request('POST', config('services.url_twitter_access_token'), [
                'auth' => 'oauth',
            ]);
            $accessToken = [];
            parse_str($accessTokenResponse->getBody(), $accessToken);

            $profileOauth = new Oauth1([
                'consumer_key' => config('services.twitter_key'),
                'consumer_secret' => config('services.twitter_secret'),
                'oauth_token' => $accessToken['oauth_token'],
                'token_secret' => config('services.TWITTER_TOKEN_SECRET'),
            ]);
            $stack->push($profileOauth);

            $client = new GuzzleHttp\Client([
                'handler' => $stack,
            ]);
            $url = 'https://api.twitter.com/1.1/users/show.json?screen_name=' . $accessToken['screen_name'];
            $profileResponse = $client->request('GET', $url, [
                'auth' => 'oauth',
            ]);
            $profile = json_decode($profileResponse->getBody(), true);
            
            return $this->createSocialUser($profile, 'twitter');
        }
    }
}
