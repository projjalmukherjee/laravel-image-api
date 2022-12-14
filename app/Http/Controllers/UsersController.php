<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        
        $data['response']['data'] = [];
        if($validator->fails()){

            $data['response']['msg'] = $validator->errors();
            return response()->json($data,404);     
        }
   
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $user->name;
        
        $data['response']['data'] =  $success;
        $data['response']['msg'] = 'User created successfully.';
        return response()->json($data,200);     
        
    }


    public function login(Request $request) {

        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $data['response']['data'] = [];

        if($validator->fails()){

            $data['response']['msg'] = $validator->errors();
            return response()->json($data,404);     
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) { 
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['name'] =  $authUser->name;
            
            $data['response']['data'] =  $success;
            $data['response']['msg'] = 'User signed in.';
            return response()->json($data,200); 
            
        } 
        else { 

            $data['response']['msg'] = 'Unauthorised.';
            return response()->json($data,401); 
        }

    }

}
