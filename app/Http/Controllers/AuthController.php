<?php
/**
 * Created by PhpStorm.
 * User: alsofronie
 * Date: 13/06/2018
 * Time: 02:14
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JsonWebTokenService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = $this->attempt($request);
        if (!$user) {
            throw new AuthenticationException('invalid_credentials');
        }

        return response()->json([
            'token' => $this->getToken($user, $request),
            'type' => 'Bearer',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required',
        ]);

        $user = $this->createUser($request->only(['email', 'password', 'name']));

        return response()->json([
            'token' => $this->getToken($user, $request),
            'type' => 'Bearer',
        ]);
    }

    /**
     * @param $request
     * @return bool|\App\Models\User
     */
    protected function attempt($request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            return false;
        }

        return (app('hash')->check($password, $user->password) ? $user : false);
    }

    protected function createUser(array $data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = app('hash')->make($data['password']);
        $user->save();

        return $user;
    }

    protected function getToken(User $user, Request $request)
    {
        $service = new JsonWebTokenService();
        $jti = $request->getClientIp();
        return $service->encode($user->id, $jti);
    }
}
