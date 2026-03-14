<?php

namespace App\Services\Auth;

class JwtService
{
    public function issueToken(array $claims, int $ttlSeconds = 3600): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload = array_merge($claims, [
            'iat' => time(),
            'exp' => time() + $ttlSeconds,
        ]);

        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR)),
            $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR)),
        ];

        $signature = hash_hmac('sha256', implode('.', $segments), $this->secret(), true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    public function decodeToken(string $token): ?array
    {
        [$encodedHeader, $encodedPayload, $encodedSignature] = explode('.', $token) + [null, null, null];
        if (!$encodedHeader || !$encodedPayload || !$encodedSignature) {
            return null;
        }

        $expected = $this->base64UrlEncode(
            hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $this->secret(), true)
        );

        if (!hash_equals($expected, $encodedSignature)) {
            return null;
        }

        $payload = json_decode(base64_decode(strtr($encodedPayload, '-_', '+/')), true);

        if (!is_array($payload) || ($payload['exp'] ?? 0) < time()) {
            return null;
        }

        return $payload;
    }

    private function secret(): string
    {
        return env('JWT_SECRET', 'change-this-secret');
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
