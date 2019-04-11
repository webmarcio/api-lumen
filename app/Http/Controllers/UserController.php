<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function __construct()
    {
        //
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:60',
            'email' => 'required|unique:users|max:80',
            'password' => 'required|max:32',
            'active' => ''
        ]);
        $user = new User($request->all());
        $user->password = Crypt::encrypt($request->input('password'));
        $user->save();
        return $user;
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:60',
            'email' => 'required|unique:users|max:80',
            'password' => 'required|max:32',
            'active' => ''
        ]);
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Crypt::encrypt($request->input('password'));
        $user->active = $request->input('active');
        $user->update();
        return $user;
    }


}
