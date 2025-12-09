<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Psy\Reflection\ReflectionLanguageConstruct;

class UserController extends Controller
{

    protected $user;


    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function index(){
        $users = User::all();
        return response()->json($users);
    }

    public function show(){

    }

    public function store(){

    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'email'        => "required|email|unique:users,email,$id",
            'username'     => "required|string|min:3|max:20|unique:users,username,$id",
            'full_name'     => 'required|string|min:2|max:50',
            'bio'          => 'nullable|string',
            'profile_image'=> 'nullable|string',  // not required
        ]);
        
        $updatedProfile = $this->user->update($fields, $id);

        if ( $updatedProfile) {
            return response()->json([
                'message' => 'Profile updated successfully!',
                'user' =>   $this->user->index($id)
            ]);
        }

        return response()->json([
            'message' => 'An error occurred!'
        ], 500);
    }


    public function destroy($id){
        if($this->user->destroy($id)){
            return response()->json([
                'message' => 'Account deleted successfully!',
                'status' => 'success'
            ]);
        }

        return response()->json([
            'message' => 'Account deleted failed!',
             'status' => 'failed'
        ]);
    }
}
