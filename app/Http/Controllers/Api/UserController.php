<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

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
}
