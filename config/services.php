<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'FACEBOOK_SECRET' => ENV('FACEBOOK_SECRET'),
    'GOOGLE_SECRET' => ENV('GOOGLE_SECRET'),
    'TWITTER_KEY' => ENV('TWITTER_KEY'),
    'TWITTER_SECRET' => ENV('TWITTER_SECRET'),
    'TWITTER_TOKEN' => ENV('TWITTER_TOKEN'),
    'TWITTER_TOKEN_SECRET' => ENV('TWITTER_TOKEN_SECRET'),

    'url_facebook_token_access' => 'https://graph.facebook.com/v2.5/oauth/access_token',
    'url_facebook_me' => 'https://graph.facebook.com/v2.5/me',
    'url_account_google_token' => 'https://accounts.google.com/o/oauth2/token',
    'url_google_me' => 'https://www.googleapis.com/plus/v1/people/me/openIdConnect',
    'url_twitter_request_token' => 'https://api.twitter.com/oauth/request_token',
    'url_twitter_access_token' => 'https://api.twitter.com/oauth/access_token',

];
