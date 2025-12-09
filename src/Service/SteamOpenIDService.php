<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class SteamOpenIDService
{
    private const STEAM_OPENID_URL = 'https://steamcommunity.com/openid/login';

    public function __construct(
        private string $realm,
        private string $returnTo,
    ) {
    }

    public function getAuthUrl(): string
    {
        $params = [
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'checkid_setup',
            'openid.return_to' => $this->returnTo,
            'openid.realm' => $this->realm,
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];

        return self::STEAM_OPENID_URL.'?'.http_build_query($params);
    }

    public function validate(Request $request): ?string
    {
        if ('id_res' !== $request->query->get('openid_mode')) {
            return null;
        }

        $claimedId = $request->query->get('openid_claimed_id');
        if (!$claimedId) {
            return null;
        }

        if (!preg_match('#^https://steamcommunity\.com/openid/id/(\d+)$#', $claimedId, $matches)) {
            return null;
        }

        $steamId = $matches[1];

        if (!$this->verifyWithSteam($request)) {
            return null;
        }

        return $steamId;
    }

    private function verifyWithSteam(Request $request): bool
    {
        $params = [
            'openid.assoc_handle' => $request->query->get('openid_assoc_handle'),
            'openid.signed' => $request->query->get('openid_signed'),
            'openid.sig' => $request->query->get('openid_sig'),
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'check_authentication',
        ];

        $signed = explode(',', $request->query->get('openid_signed', ''));
        foreach ($signed as $item) {
            $paramName = 'openid_'.str_replace('.', '_', $item);
            $value = $request->query->get($paramName);
            if (null !== $value) {
                $params['openid.'.$item] = $value;
            }
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($params),
                'timeout' => 10,
            ],
        ]);

        $response = @file_get_contents(self::STEAM_OPENID_URL, false, $context);

        if (false === $response) {
            return false;
        }

        return str_contains($response, 'is_valid:true');
    }
}
