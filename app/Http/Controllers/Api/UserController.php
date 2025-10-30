<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:manage-users');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info("Fetching all users");
        $users = User::all();
        return $users->map(function ($user) {
            $user->roles = $user->roles()->pluck('name');
            return $user;
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        
        $validated = $request->validated();

        $user = User::create($request->getData());
        $user->assignRole($validated['role']);
        \Log::info("Created new user with ID: " . $user->id);
        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info("Fetching user with ID: " . $id);
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        \Log::info("Raw Request All:", $request->all());
        $user = User::findOrFail($id);
        $requestData = $request->getData();
        \Log::info("Update request data: " . json_encode($requestData));
        $userData = $user->only(array_keys($requestData)); 
        $diff = array_diff_assoc($requestData, $userData);
        if (empty($diff)) {
            \Log::info("No changes detected for user with ID: " . $id);
            return response()->json($user, Response::HTTP_OK);
        }
        $user->fill($requestData)->save();
        \Log::info("Updated user with ID: " . $id);
        return response()->json($user, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->email = $user->email . '.deleted.' . time();
        $user->saveQuietly();
        $user->delete();
        $user->refresh();
        
        \Log::info("Deleted user with ID: " . $id . ", deleted_at: " . $user->deleted_at);

        return response()->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if ($user->deleted_at) {
            $user->email = preg_replace('/\.deleted\.\d+$/', '', $user->email);
            $user->saveQuietly();
            $user->restore();
            \Log::info("Restored user with ID: " . $id);
            return response()->json(['message' => 'User restored successfully'], Response::HTTP_OK);
        } else {
            \Log::info("User with ID: " . $id . " is not deleted, cannot restore.");
            return response()->json(['message' => 'User is not deleted'], Response::HTTP_BAD_REQUEST);
        }
    }

}
