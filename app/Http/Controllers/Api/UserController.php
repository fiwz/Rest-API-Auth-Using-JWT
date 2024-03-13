<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class UserController extends Controller
{
    protected $user;

    public function __construct (User $user)
    {
        $this->user = $user;
    }

    /**
     * Retrieve authenticated user data
     *
     * @return void
     */
    public function me ()
    {
        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'messsage' => 'User fetched successfully!'
            ],
            'data' => [
                'user' => auth()->user()
            ]
        ], Response::HTTP_OK);
    }

    /**
     * getList
     *
     * provide list of users
     */
    public function getList(Request $request)
    {
        $list = $this->user->select('name', 'email');
        if (isset($request['search']) && !empty($request['search'])) {
            $list = $list->where('name', 'like', '%'.$request['search'].'%')
                ->orWhere('email', 'like', '%'.$request['search'].'%');
        }
        $list = $list->orderBy('name', 'ASC')->get();
        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success',
                'messsage' => 'Data fetched successfully!'
            ],
            'data' => [
                'user' => $list
            ]
        ], Response::HTTP_OK);
    }

    /**
     * store
     *
     * create new record of user in database
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        // option #2 to do this
        // $validate = //code// ->validate()
        // no need to do if fails()

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error while validating data!',
                'data' => [],
                'errors' => $validate->errors()
            ], SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $form_data = $request->all();
        $form_data['password'] = bcrypt($form_data['password']);
        $store_data = $this->user->create($form_data);
        if ($store_data) {
            return response()->json([
                'success' => true,
                'message' => 'User successfully stored',
                'data' => [],
                'errors' => []
            ], SymfonyResponse::HTTP_CREATED);
        }

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong, try again in a moment!',
            'data' => [],
            'errors' => [],
        ], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
