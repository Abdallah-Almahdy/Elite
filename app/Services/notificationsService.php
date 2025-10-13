<?php

namespace App\Services;

use Google_Client;

class notificationsService
{
    public function getServerKeyToken()
    {
        // Path to your service account key file
        $user   = get_current_user();
        $domain = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];

        //  $keyFilePath = "/home/{$user}/htdocs/{$domain}/app/Services/resturant-38fd1-firebase-adminsdk-fbsvc-1975b1ebef.json";


        $base64Key = env('FIREBASE_CREDENTIALS_BASE64');

        if (!$base64Key) {
            throw new \Exception('Firebase credentials not found in .env');
        }

        // فك التشفير وخلّيه Array مباشرة
        $credentials = json_decode(base64_decode($base64Key), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid Firebase credentials format');
        }

        $client = new Google_Client();
        $client->setAuthConfig($credentials);

        // Set the scopes required for your application
        $client->setScopes([
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/firebase.database',
            'https://www.googleapis.com/auth/firebase.messaging',
        ]);

        // Fetch the access token
        try {
            $client->fetchAccessTokenWithAssertion();
            $accessToken = $client->getAccessToken()['access_token'];

            // Output the Access Token
            // echo "Access Token: " . $accessToken;

            return $accessToken;
        } catch (\Exception $e) {
            echo 'Error getting access token: ',  $e->getMessage();
            return null;
        }
    }
}
