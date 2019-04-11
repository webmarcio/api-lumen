<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function getAllUsers()
    {
        $users = User::all();
        return $users;
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

    public function delete($id)
    {
        if (User::destroy($id)) {
            return new Response('user successfully removed!', 200);
        } else {
            return new Response('Sorry, there was an error while trying to remove this user.', 401);
        }
    }

}
