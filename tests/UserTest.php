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
            'name' => str_random(60),
            'email' => str_random(30).'@exemplo.com',
            'password' => '123456',
            'active' => 1
        ];
    }

    public function testCreateUser()
    {
        $this->post('/api/user', $this->data);
        // echo $this->response->content();
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);

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
}
