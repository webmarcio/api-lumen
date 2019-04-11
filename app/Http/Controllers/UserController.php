<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->middleware('auth:api',[
            'except' => [
                'create',
                'login'
            ]
        ]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:60',
            'email' => 'required|email|unique:users|max:80',
            'password' => 'required|confirmed|max:32',
            'active' => ''
        ]);
        $user = new User($request->all());
        // $user->password = Crypt::encrypt($request->input('password'));
        $user->password = Hash::make($request->input('password'));
        $user->api_token = str_random(60);
        $user->save();
        return $user;
    }

    public function login(Request $request)
    {
        if(!$token = $this->jwt->claims(['email' => $request->input('email')])->attempt($request->only('email', 'password'))){
            return new Response('Dados inválidos. Informe os dados corretamente.', 401);
        }
        return Response()->json(compact('token'));
    }

    public function viewUserAuth()
    {
        $user = Auth::user();
        return Response()->json($user);
    }

    public function logout()
    {
        Auth::logout();
        return Response()->json('Logout', 200);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function getAllUsers()
    {
        $users = User::all();
        return $users;
    }

    public function update(Request $request, $id)
    {
        $dataValidate = [
            'name' => 'required|max:60',
            'email' => 'required|email|unique:users|max:80'
        ];

        if (isset($request->all()['password'])) {
            $dataValidate['password'] = 'required|confirmed|max:32';
        }

        if (isset($request->all()['active'])) {
            $dataValidate['active'] = '';
        }

        $this->validate($request, $dataValidate);
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if (isset($request->all()['password'])) {
            $user->password = Crypt::encrypt($request->input('password'));
        }

        if (isset($request->all()['active'])) {
            $user->active = $request->input('active');
        }

        $user->update();
        return $user;
    }

    public function delete($id)
    {
        if (User::destroy($id)) {
            return new Response('user successfully removed!', 200);
        } else {
            return new Response('Sorry, there was an error while trying to remove this user.', 401);
        }
    }

}
