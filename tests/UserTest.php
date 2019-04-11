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
            'active' => 1,
            // Usar um token gerado pela função testLogin()
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDAyMCwiZXhwIjoxNTU1MDA3NjIwLCJuYmYiOjE1NTUwMDQwMjEsImp0aSI6IkJ5V0xsc2FFSm16RTJoNmkiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.toWWktW0iVVgG19UDNzWQehJVO10b48mAdWuwNk2lSI'
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

    public function testLogin()
    {
        $data = [
            'email' => 'WUF9oCmNguYuUFTZs6IEPVwtE07JxNZ0UTgvuSlx@exemplo.com',
            'password' => '123456'
        ];

        $this->post('/api/login', $data);
        $this->assertResponseOk();
        $res = (array) json_decode($this->response->content());
        $this->assertArrayHasKey('token', $res);
    }

    public function testInfo()
    {

        $data = [
            // Usar um token gerado pela função testLogin()
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwMzkxMSwiZXhwIjoxNTU1MDA3NTExLCJuYmYiOjE1NTUwMDM5MTEsImp0aSI6Ik5DVUNLd2l5em9vTHZIdGsiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.rzjd8jjgJUZOePOxfiraAjO5d_OcrJcXdur1lKnlAyA'
        ];
        $this->post('/api/info', $data);
        $this->assertResponseOk();
    }

    public function testLogout()
    {
        $data = [
            // Usar um token gerado pela função testLogin() porém diferente do usado na função testInfo()
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDAyMCwiZXhwIjoxNTU1MDA3NjIwLCJuYmYiOjE1NTUwMDQwMjEsImp0aSI6IkJ5V0xsc2FFSm16RTJoNmkiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.toWWktW0iVVgG19UDNzWQehJVO10b48mAdWuwNk2lSI'
        ];
        $this->post('/api/logout', $data);
        $this->assertResponseOk();
    }

    public function testGetUser()
    {
        $user = \App\User::first();
        // Usar um token gerado pela função testLogin()
        $this->get('/api/user/'.$user->id.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDE4OSwiZXhwIjoxNTU1MDA3Nzg5LCJuYmYiOjE1NTUwMDQxODksImp0aSI6IkYwc1c2aXNQSEVBU0hPVFgiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.ZBk_j2nHWQM8o64Er8Hiz5UCKc6ekuxzVbeUuT4wN_I');
        $this->assertResponseOk();

        $res = (array) json_decode($this->response->content());

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertArrayHasKey('email', $res);
        $this->assertArrayHasKey('active', $res);
    }

    public function testGetAllUsers()
    {
        // Usar um token gerado pela função testLogin()
        $this->get('/api/users?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDE4OSwiZXhwIjoxNTU1MDA3Nzg5LCJuYmYiOjE1NTUwMDQxODksImp0aSI6IkYwc1c2aXNQSEVBU0hPVFgiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.ZBk_j2nHWQM8o64Er8Hiz5UCKc6ekuxzVbeUuT4wN_I');
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
            'active' => 1,
            // Usar um token gerado pela função testLogin()
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDAyMCwiZXhwIjoxNTU1MDA3NjIwLCJuYmYiOjE1NTUwMDQwMjEsImp0aSI6IkJ5V0xsc2FFSm16RTJoNmkiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.toWWktW0iVVgG19UDNzWQehJVO10b48mAdWuwNk2lSI'
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
            'password_confirmation' => '123456',
            // Usar um token gerado pela função testLogin()
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDAyMCwiZXhwIjoxNTU1MDA3NjIwLCJuYmYiOjE1NTUwMDQwMjEsImp0aSI6IkJ5V0xsc2FFSm16RTJoNmkiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.toWWktW0iVVgG19UDNzWQehJVO10b48mAdWuwNk2lSI'
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
        $data = [
            // Usar um token gerado pela função testLogin() porém diferente do usado na função testInfo()
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMVwvYXBpXC9sb2dpbiIsImlhdCI6MTU1NTAwNDAyMCwiZXhwIjoxNTU1MDA3NjIwLCJuYmYiOjE1NTUwMDQwMjEsImp0aSI6IkJ5V0xsc2FFSm16RTJoNmkiLCJzdWIiOjk1LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZW1haWwiOiJuOTZJVnBqSVlhUHJyZWV4clBkR0RLWEV6emZreHJoUW9kVkYxeUpuQGV4ZW1wbG8uY29tIn0.toWWktW0iVVgG19UDNzWQehJVO10b48mAdWuwNk2lSI'
        ];
        $user = \App\User::first();
        $this->delete('/api/user/' . $user->id, $data);
        $this->assertResponseOk();
        $this->assertEquals('user successfully removed!', $this->response->content());
    }
}
