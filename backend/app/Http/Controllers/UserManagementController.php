<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // Get all users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Update only role and status
    public function updateRoleStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:Admin,User',
            'status' => 'required|in:Active,Inactive,Blocked',
        ]);

        $user = User::findOrFail($id);
        
        // Only update role and status
        $user->role = $validated['role'];
        $user->status = $validated['status'];
        $user->save();
        
        return response()->json($user);
    }

    // Delete user
    public function destroy($id)
    {

    }
}