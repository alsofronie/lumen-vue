<?php
/**
 * Date: 31/05/2018
 * Time: 22:59
 */

namespace App\Http\Middleware;

use \Closure;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = $this->getHeaders();
        if ($request->isMethod('options')) {
            return response('', 200, $headers);
        }

        $response = $next($request);

        // Specially for downloading operations. We cannot add headers to this monster
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        return $response->withHeaders($headers);
    }

    protected function getHeaders()
    {
        return [
            'Access-Control-Allow-Origin' => env('WEB_CLIENT_ORIGIN'),
            'Access-Control-Allow-Headers' => implode(',', [
                'Origin', 'Authorization',
                'Content-Type', 'Content-Length', 'Accept',
                'X-Requested-With', 'X-Api-Token',
            ]),
            'Access-Control-Allow-Methods' => implode(',', [
                'GET', 'POST', 'PUT', 'PATCH', 'DELETE',
            ]),
        ];
    }
}
