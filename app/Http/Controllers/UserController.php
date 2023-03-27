<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeRoleRequest;
use App\Http\Requests\UpdateNameEmailUserRequest;
use App\Http\Requests\UpdatePasswordUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = Auth::user();
         
        if ( !$user->hasPermissionTo('read all profiles') && !$user->hasPermissionTo('read my profile') ){
            return response()->json([
                'status' => false,
                'message' => 'you don\'t have access',
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully!',
            'data' => User::all(),
        ], Response::HTTP_OK);
    }
    
    public function show(User $user)
    {
        $user->find($user->id);
        if (!$user->hasPermissionTo('read my profile')) {
            return response()->json(['message' => 'user not found'], 404);
        }
        return response()->json($user, 200);
    }

    public function updateNameEmail(UpdateNameEmailUserRequest $request, User $user)
    {
        $userauth = Auth::user();
        
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'user not found',
            ], 404);
        }

        if (!$userauth->hasPermissionTo('edit profil') && !$userauth->hasPermissionTo('edit my profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You don\'t have permission to Update this user'
            ], Response::HTTP_FORBIDDEN);
        }

        // $user->update($request->validated());
        $user->update($request->all());

        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
        ], Response::HTTP_OK);
    }    

    public function destroy(User $user)
    {
        $userauth = Auth::user();
        if (!$userauth->can('delete all profils | delete my profil') && $userauth->id != $user->id) {
           return response()->json([
                'status' => false,
                'message' => "You don't have permission to delete this user!",
            ], Response::HTTP_FORBIDDEN);
        }
        $user->delete();

         return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
}
