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
        $keyFilePath = base_path('app/Services/resturant-38fd1-firebase-adminsdk-fbsvc-1975b1ebef.json');



        // Create a Google client

        $client = new Google_Client();

        $client->setAuthConfig($keyFilePath);

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
