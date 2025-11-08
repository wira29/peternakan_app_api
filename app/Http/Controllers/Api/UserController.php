<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-users');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        \Log::info('Fetching all users');

        $users = User::all();

        return $users->map(function ($user) {
            $user->roles = $user->roles()->pluck('name');
            $user->created_by = $user->createdby();
            $user->updated_by = $user->updatedBy();
            $user->deleted_by = $user->deletedBy();

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
        $user->roles = $user->roles()->pluck('name');
        $user->created_by = $user->createdby();
        $user->updated_by = $user->updatedBy();
        $user->deleted_by = $user->deletedBy();
        \Log::info('Created new user with ID: '.$user->id);

        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        \Log::info('Fetching user with ID: '.$id);
        $user = User::findOrFail($id);
        $user->roles = $user->roles()->pluck('name');
        $user->created_by = $user->createdby();
        $user->updated_by = $user->updatedBy();
        $user->deleted_by = $user->deletedBy();

        return response()->json($user, Response::HTTP_OK);
    }

    public function getCurrentUser(Request $request)
    {
        try {
            $user = $request->user();
            $user->makeHidden(['roles', 'permissions']);
            $userData = $user->toArray();

            $userData['roles'] = $user->roles()->pluck('name');
            $userData['permissions'] = $user->permissions()->pluck('name');

            \Log::info('User retrieved by token', [
                'user_id' => $user->id,
                'token_name' => $request->user()->currentAccessToken()->name ?? 'unknown',
            ]);

            return response()->json($userData, Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error('Failed to retrieve user by token', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {

        try {
            $requestData = $request->all();
            \Log::info('Update request data: '.json_encode($requestData));

            $rolesData = $requestData['roles'] ?? null;
            $userDataRequest = array_diff_key($requestData, ['roles' => '']);

            $user = User::findOrFail($id);
            $userData = $user->only(array_keys($userDataRequest));
            \Log::info('Existing user data: '.json_encode($userData));

            $currentRoles = $user->roles()->pluck('name')->toArray();
            \Log::info('Current roles: '.json_encode($currentRoles));

            $userDataDiff = array_diff_assoc($userDataRequest, $userData);

            $rolesChanged = false;
            if ($rolesData !== null) {
                $rolesChanged = ($rolesData !== $currentRoles);
            }

            if (empty($userDataDiff) && ! $rolesChanged) {
                \Log::info('No changes detected for user with ID: '.$id);

                return response()->json($user, Response::HTTP_OK);
            }

            if (! empty($userDataDiff)) {
                $user->update($userDataRequest);
                \Log::info('Updated user data with ID: '.$id);
            }

            if ($rolesChanged) {
                $user->syncRoles($rolesData);
                \Log::info('Synced roles for user with ID: '.$id);
            }

            $user->refresh();
            $user->roles = $user->roles()->pluck('name');
            $user->created_by = $user->createdby();
            $user->updated_by = $user->updatedBy();
            $user->deleted_by = $user->deletedBy();

            \Log::info('Successfully updated user with ID: '.$id);

            return response()->json($user, Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error('Error fetching user with ID: '.$id.' - '.$e->getMessage());

            return response()->json(['message' => 'Update failed'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->email = $user->email.'.deleted.'.time();
        $user->saveQuietly();
        $user->delete();
        $user->refresh();

        \Log::info('Deleted user with ID: '.$id.', deleted_at: '.$user->deleted_at);

        return response()->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    public function restore(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if ($user->deleted_at) {
            $user->email = preg_replace('/\.deleted\.\d+$/', '', $user->email);
            $user->saveQuietly();
            $user->restore();
            \Log::info('Restored user with ID: '.$id);

            return response()->json(['message' => 'User restored successfully'], Response::HTTP_OK);
        } else {
            \Log::info('User with ID: '.$id.' is not deleted, cannot restore.');

            return response()->json(['message' => 'User is not deleted'], Response::HTTP_BAD_REQUEST);
        }
    }
}
