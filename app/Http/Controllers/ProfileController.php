<?php
/**
 * Created by PhpStorm.
 * User: alsofronie
 * Date: 13/06/2018
 * Time: 02:19
 */

namespace App\Http\Controllers;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $this->getLogged($request);

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws AuthorizationException
     */
    public function update(Request $request)
    {
        $user = $this->getLogged($request);

        $this->validate($request, [
            'name' => 'sometimes|required|string|min:3',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6|max:100',
            'password_old' => 'sometimes|required_with:password',
        ]);
        $name = $request->input('name');
        if ($name) {
            $user->name = $name;
        }
        $email = $request->input('email');
        if ($email) {
            $user->email = $email;
        }
        $password = $request->input('password');
        if ($password) {
            $oldPassword = $request->input('password_old');
            if (!app('hash')->check($oldPassword, $user->password)) {
                throw new AuthorizationException('wrong_password');
            }
            $user->password = app('hash')->make($password);
        }
        $user->save();

        return response()->json([
            'user' => $user,
        ]);
    }
}
