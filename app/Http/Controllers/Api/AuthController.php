<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user; // model as dependency injection
    }

    public function register(Request $request)
    {
        // validate the incoming request
        // set every field as required
        // set email field so it only accept the valid email format

        $this->validate($request, [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|min:6|max:250'
        ]);

        // other validation methods #1
        // $request->validate($rules);

        // other validation methods #2
        // $test = $request->validate([
        //     'name' => 'required|min:2|max:255'
        // ]);

        // other validation methods #3
        // use Illuminate\Support\Facades\Validator;
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|min:2|max:255'
        // ]);
        // if ($validator->fails()) {
        //     return redirect('post/create')
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        // if the request is valid, create user
        $form_data = $request->all();
        $form_data['password'] = bcrypt($form_data['password']);
        $user = $this->user->create($form_data);

        // login the user immediately and generate token
        $token = auth()->login($user);

        // return response as json
        return response()->json([
            'meta' => [
                'code' => Response::HTTP_CREATED,
                'status' => 'success',
                'message' => 'Successfully registered!'
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ]
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // attempt a login
        $token = auth()->attempt($request->only(['email', 'password']));

        // if token successfully generated then display success response
        // if attempt failed then "unauthenticated" will be returned automatically
        if ($token) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Quote fetched successfully.'
                ],
                'data' => [
                    'user' => auth()->user(),
                    'access_token' => [
                        'token' => $token,
                        'type' => 'Bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60
                    ]
                ]
            ], Response::HTTP_OK);
        }

        // throw error option #1
        // throw(new \Exception('Wrong credentials!'));
        // throw(new \Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException('Wrong credentials!'));

        // return error response
        // format response do not consistent yet, next will fix it
        return response()->json([
            'error' => 'Bad Request',
            'success' => false,
            'message' => 'Wrong credentials!',
        ], SymfonyResponse::HTTP_BAD_REQUEST);
    }

    public function logout ()
    {
        // get token
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);

        if ($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out.'
                ],
                'data' => []
            ], Response::HTTP_OK);
        }

        return response()->json([
            'error' => 'Internal Server Error',
            'success' => false,
            'message' => 'Something went wrong, try again in a moment!',
        ], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
