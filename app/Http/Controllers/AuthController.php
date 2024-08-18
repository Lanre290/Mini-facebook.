<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    
    public function signup(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'pwd' => 'required|string|max:255'
        ]);

        $name = $request->name;
        $email = $request->email;
        $pwd = $request->pwd;

        $emailExists = DB::table('user')
                        ->where('email',$email)
                        ->count();

        if(empty($name) || empty($email) || empty($pwd)){
            $data = 'Error processing data.';
            return response(['error' => 'Error processing data.','data' => ''], 422);
        }
        if($emailExists > 0){
            $data = 'Email already exists.';
            return response()->json(['error' => 'Email already exists.','data' => ''], 409 );
        }

        Users::create([
            'name' => $name,
            'email' => $email,
            'pwd' => Hash::make($pwd), 
            'bio' => '',
            'image_path' => ''
        ]);

        return response()->json(['data' => 'ok'], 200);
    }

    public function login(Request $request){
        try {
            $request->validate([
                'email' => 'required|string',
                'pwd' => 'required|string'
            ]);
    
            $email = $request->email;
            $pwd = $request->pwd;
    
            if(empty($email) || empty($pwd)){
                return response()->json(['error' => 'Error processing data.','data' => ''], 422);
            }
            else{
                $emailExists = Users::where('email', $email)->count();
                if($emailExists < 1){
                    return response()->json(['error' => 'Email does not exist.', 'data' => ''], 404);
                }
                else{
                    $Hashedpwd = Users::where('email', $email)->pluck('pwd')->first();
                    
                    if(Hash::check($pwd, $Hashedpwd)){
                        $userData = Users::where('email', $email)->select('id','email','name','bio','image_path','cover_img_path')->first();
                        session(['user' => $userData]);
                        if(session('user')->image_path == ''){
                            session('user')->image_path = asset('img/users_dp/default.png');
                        }
                        return response(['data' => true], 200);
                    }
                    else{
                        return response(['error' => 'Incorrect password.', 'data' => ''], 404);
                    }
                }
            }
        } catch (\Throwable $th) {
            return response(['error' => $th]);
        }
    }

    public function logout(Request $request){

        session()->regenerateToken();

        session()->flush();
    }

}