<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    // Testing Auth
    // register,login,logout , and get user info
    public function testRegisterWithValidData()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/register', $data);




        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'success',
            'user' => [
                'name',
                'email',
                'updated_at',
                'created_at',
                'id',
            ],
        ]);
    }

    public function testRegisterWithExistingEmail()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];


        $response = $this->post('/api/v1/auth/register', $data);

        // with format error
        // errors : {
        //     "email": [
        //         "The email has already been taken."
        //     ]

        $response->assertStatus(422)->assertJson(
            [
                'errors' => [
                    'email' => [
                        'The email has already been taken.'
                    ]
                ]
            ]
        );
    }

    // register with email not valid
    public function testRegisterWithInvalidEmail()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoeexample.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/register', $data);

        // with format error
        // errors : {
        //     "email": [
        //         "The email must be a valid email address."
        //     ]
        // }

        $response->assertStatus(422)->assertJson(
            [
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]


        );
    }

    // register with password less than 6 characters
    public function testRegisterWithInvalidPassword()
    {
        $data = [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'pass',
        ];

        $response = $this->post('/api/v1/auth/register', $data);

        // with format error
        // errors : {
        //     "password": [
        //         "The password must be at least 6 characters."
        //     ]
        // }

        $response->assertStatus(422)->assertJson(
            [
                'errors' => [
                    'password' => [
                        'The password field must be at least 6 characters.'
                    ]
                ]
            ]

        );
    }

    // register with empty name
    public function testRegisterWithEmptyName()
    {
        $data = [
            'name' => '',
            'email' => 'johndoe@example.com',
            'password' => 'password',

        ];

        $response = $this->post('/api/v1/auth/register', $data);

        // with format error
        // errors : {
        //     "name": [
        //         "The name field is required."
        //     ]
        // }

        $response->assertStatus(422)->assertJson(
            [
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]

        );
    }

    public function testRegisterWithEmptyEmail()
    {
        $data = [
            'name' => 'Jhon Doe',
            'email' => '',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/register', $data);

        $response->assertStatus(422)->assertJson(
            [
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ]
                ]
            ]
        );
    }

    public function testRegisterWithEmptyPassword()
    {
        $data = [
            'name' => 'Jhon',
            'email' => 'Jhon@gmail.com',
            'password' => '',
        ];

        $response = $this->post('/api/v1/auth/register', $data);

        $response->assertStatus(422)->assertJson(
            [
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ]
        );
    }

    public function testLoginWithValidCredentials()
    {
        $data = [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/login', $data);

        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'success',
            'user' => [
                'name',
                'email',
                'updated_at',
                'created_at',
                'id',
            ],
        ]);
    }

    public function testLoginWithInvalidCredentials()
    {
        $data = [
            'email' => 'johndoe@example.com',
            'password' => 'password_wrong',
        ];

        $response = $this->post('/api/v1/auth/login', $data);

        $response->assertStatus(401)->assertJson([
            'message' => 'Invalid Email or Password',
        ]);
    }

    public function testLoginWithEmptyCredentials()
    {
        $data = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->post('/api/v1/auth/login', $data);

        $response->assertStatus(422)->assertJson([
            'errors' => [
                'email' => [
                    'The email field is required.',
                ],
                'password' => [
                    'The password field is required.',
                ],
            ],
        ]);
    }
    public function testGetUserWithValidToken()
    {
       // login
        $data = [
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/auth/login', $data);


        // get token
        $token = $response->json('access_token');

        // get data from api me
        // make request to the API with the token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v1/auth/me');

        // format
        // {
            // data
            // "name",
            // "email",
            // "email_verified_at",
            // "updated_at",
            // "created_at",
            // "id",
        // }

        $response->assertStatus(200);



    }





    // get user data












    // login with valid data

}
