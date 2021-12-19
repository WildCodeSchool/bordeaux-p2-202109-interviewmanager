<?php

namespace App\Service;

use App\Model\UserManager;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class GitLogger
{
    private string $code;
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getUser(): array
    {
        $client = HttpClient::create();
        $fields = [
            'client_id' => GIT_CLIENT,
            'client_secret' => GIT_SECRET,
            'code' => $this->code,
            'redirect_uri' => REDIRECT_URI
        ];
        $formData = new FormDataPart($fields);
        $response = $client->request(
            'POST',
            'https://github.com/login/oauth/access_token',
            [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]
        );
        $params = [];
        parse_str($response->getContent(), $params);
        $response = $client->request(
            'GET',
            'https://api.github.com/user',
            [
                'headers' => ['Authorization' => $params['token_type'] . ' ' . $params['access_token']],
            ]
        );
        $userData = json_decode($response->getContent());
        return [
            'profil_github' => $userData->login,
        ];
    }
    public function getAndPersist($userData): array
    {
        $userManager = new UserManager();
        if (!$userManager->selectOneByPseudo($userData['profil_github'])) {
            $userId = $userManager->create($userData);
            $user = $userManager->selectOneById((int)$userId);
        } else {
            $user = $userManager->selectOneByPseudo($userData['profil_github']);
        }
        return $user;
    }
}
