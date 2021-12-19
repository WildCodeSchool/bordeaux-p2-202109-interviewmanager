<?php

namespace App\Service;

use App\Model\UserManager;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class GoogleLogger
{
    private string $code;
    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getUser()
    {
        $client = HttpClient::create();
        $response = $client->request(
            'POST',
            'https://oauth2.googleapis.com/token',
            [
                'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
                'body' => [
                    'code' => $this->code,
                    'client_id' => GOOGLE_ID,
                    'client_secret' => GOOGLE_CLIENT_SECRET,
                    'redirect_uri' => GOOGLE_REDIRECT_URI,
                    'grant_type' => 'authorization_code',
                ]
            ]
        );
        $params = json_decode($response->getContent());
        $response = $client->request(
            'GET',
            'https://openidconnect.googleapis.com/v1/userinfo',
            [
                'headers' => ['Authorization' => 'Bearer ' . $params->access_token]
            ]
        );
        $userData = json_decode($response->getContent());
        return [
            'name' => $userData->name,
            'email' => $userData->email,
        ];
    }

    public function getAndPersist($user)
    {
        $userManager = new UserManager();
        if (!$userManager->selectOneByEmail($user['email'])) {
            $userId = $userManager->createWithGoogle($user);
            $user = $userManager->selectOneById($userId);
        } else {
            $user = $userManager->selectOneByEmail($user['email']);
        }
        return $user;
    }
}
