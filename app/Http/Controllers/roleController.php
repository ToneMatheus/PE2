<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\User;

class UserRoleController extends Controller
{
    public function fetchUsersByRole(Request $request)
    {
        $roleName = $request->query('roleName');
        $role = Role::where('role_name', $roleName)->first();

        if (!$role) {
            return response()->json([]);
        }

        $userIds = UserRole::where('role_id', $role->id)
                            ->where('active', 1)
                            ->pluck('user_id');

        $users = User::whereIn('id', $userIds)
                     ->where('active', 1)
                     ->get(['first_name', 'last_name']);

        $userNames = $users->map(function($user) {
            return $user->first_name . ' ' . $user->last_name;
        });

        return response()->json($userNames);
    }
}
