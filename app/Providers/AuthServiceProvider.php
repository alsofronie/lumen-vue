<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\JsonWebTokenService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // The Authentication is handled by the token sent with every request
        // The encode / decode is extracted in a service

        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $this->getToken($request);
            if (!$token) {
                return null;
            }

            return $this->getUser($token, $request);
        });
    }

    protected function getToken(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            $token = $request->input('_api_token');
        }

        return $token;
    }

    protected function getUser($token, Request $request)
    {
        try {
            $service = new JsonWebTokenService();
            $jti = $request->getClientIp();
            $userId = $service->decode($token, $jti);
            if (!$userId) {
                return null;
            }
            return User::find($userId);
        } catch (\Exception $ex) {
            // Here all the exceptions from the decoding process
            // are killed, so we don't know if there is an expired
            // token, an invalid signature ...
            return null;
        }
    }
}
