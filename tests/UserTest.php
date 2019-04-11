<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    public $data = [];

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->data = [
            'name' => str_random(50),
            'email' => str_random(40).'@exemplo.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'active' => 1
        ];
    }

    public function testCreateUser()
    {
        $this->post('/api/user', $this->data);
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);

        $this->seeInDatabase('users', [
            'name' => $this->data['name'],
            'email' => $this->data['email']
        ]);
    }

    public function testGetUser()
    {
        $user = \App\User::first();
        $this->get('/api/user/'.$user->id);
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);
    }

    public function testGetAllUsers()
    {
        $this->get('/api/users');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            '*' => [
                'id',
                'name',
                'email',
                'active'
            ]
        ]);
    }

    public function testUpdateUserWithPassword()
    {
        $user = \App\User::first();
        $this->put('/api/user/'.$user->id, $this->data);
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);

        $this->notSeeInDatabase('users', [
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);
    }

    public function testUpdateUserNoPassword()
    {
        $data = [
            'name' => str_random(50),
            'email' => str_random(40).'@exemplo.com',
            'active' => 1
        ];

        $user = \App\User::first();
        $this->put('/api/user/'.$user->id, $data);
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);

        $this->notSeeInDatabase('users', [
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);
    }

    public function testUpdateUserNoActive()
    {
        $data = [
            'name' => str_random(50),
            'email' => str_random(40).'@exemplo.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $user = \App\User::first();
        $this->put('/api/user/'.$user->id, $data);
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);

        $this->notSeeInDatabase('users', [
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);
    }

    public function testDeleteUser()
    {
        $user = \App\User::first();
        $this->delete('/api/user/' . $user->id);
        $this->assertResponseOk();
        $this->assertEquals('user successfully removed!', $this->response->content());
    }
}
