<?php
/**
 * File JsonWebTokenService.php
 *
 * PHP Version 7
 *
 * @author Alex Sofronie <alsofronie@gmail.com>
 */

namespace App\Services;

use Carbon\Carbon;
use Firebase\JWT\JWT;

class JsonWebTokenService
{
    const AUTH_KEY = 'sub';
    const AUTH_JTI = 'jti';

    protected $expiresAfter;
    protected $keys;
    protected $alg;

    public function __construct()
    {
        $this->expiresAfter = (int)env('JWT_EXPIRES', 60 * 60 * 12);
        if (env('JWT_PRIVATE_KEY') && env('JWT_PUBLIC_KEY')) {
            $this->keys = [
                'private' => env('JWT_PRIVATE_KEY'),
                'public' => env('JWT_PUBLIC_KEY')
            ];
        } else {
            if (env('JWT_KEY')) {
                $this->keys = [
                    'private' => env('JWT_KEY'),
                    'public' => env('JWT_KEY')
                ];
            } else {
                $this->keys = [
                    'private' => env('APP_KEY'),
                    'public' => env('APP_KEY')
                ];
            }
        }

        $this->alg = env('JWT_ALG', 'HS256');
    }

    public function encodePayload(array $payload)
    {
        return JWT::encode($payload, $this->keys['private'], $this->alg);
    }

    public function decodePayload(string $token)
    {
        return (array)JWT::decode($token, $this->keys['public'], [$this->alg]);
    }

    public function encode($id, $jti)
    {
        $payload = array_merge($this->payload(), [
            self::AUTH_KEY => $id,
            self::AUTH_JTI => md5('key-jti-' . $jti)
        ]);

        return $this->encodePayload($payload);
    }

    public function decode(string $token, string $jti)
    {
        $payload = $this->decodePayload($token);
        $jti = md5('key-jti-' . $jti);
        if ($payload[self::AUTH_JTI] !== $jti) {
            return null;
        }

        return $payload[self::AUTH_KEY];
    }

    protected function payload(Carbon $issuedAt = null)
    {
        if ($issuedAt === null) {
            $issuedAt = Carbon::now();
        }

        return [
            'iss' => url('/'),
            'aud' => '*',
            'iat' => $issuedAt->timestamp,
            'exp' => $issuedAt->copy()->addSeconds($this->expiresAfter)->timestamp,
            'nbf' => $issuedAt->copy()->subSecond(1)->timestamp,
        ];
    }
}
