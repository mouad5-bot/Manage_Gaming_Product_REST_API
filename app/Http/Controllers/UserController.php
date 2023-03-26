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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->can('read all profiles | read my profile')) {
            return response()->json([
                'status' => true,
                'message' => 'User retrieved successfully!',
                'data' => new UserResource($user),
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully!',
            'data' => UserResource::collection(User::all()),
        ], Response::HTTP_OK);
    }
    
    public function show(User $user)
    {
        $user->find($user->id);
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        return response()->json($user, 200);
    }

    public function updateNameEmail(UpdateNameEmailUserRequest $request, User $user)
    {
        $userauth = Auth::user();
        if (!$userauth->can('edit profil | edit my profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You dont have permission to Update this user'
            ], Response::HTTP_FORBIDDEN);
        }

        $user->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    public function updatePassword(UpdatePasswordUserRequest $request, User $user)
    {

        $userauth = Auth::user();

        if (!$userauth->can('edit profil | edit my profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You dont  have permission to Update this user'
            ], Response::HTTP_FORBIDDEN);
        }
        $user->update([
            'password' => Hash::make($request->validated())
        ]);

        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }


    // public function changeRole(ChangeRoleRequest $request,User $user)
    // {
    //     $user->syncRoles($request->validated());

    //     return response()->json([
    //         'status' => true,
    //         'message' => "User updated successfully!",
    //         'data' => new UserResource($user)
    //     ], Response::HTTP_OK);
    // }
    

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
