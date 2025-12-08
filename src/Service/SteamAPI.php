<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SteamAPI
{
    public const BASE_URL = 'http://api.steampowered.com';
    public HttpClientInterface $client;
    private string $APIKey;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->APIKey = $_ENV['STEAM_API_KEY'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getUserGames(string $userSteamId): array
    {
        if ('not_found' == $userSteamId) {
            return [];
        }

        return $this->client->request('GET', self::BASE_URL.'/IPlayerService/GetOwnedGames/v0001/', [
            'query' => [
                'key' => $this->APIKey,
                'steamid' => $userSteamId,
                'format' => 'json',
                'include_appinfo' => 'true',
            ],
        ])->toArray()['response']['games'];
    }
}
