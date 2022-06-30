<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login']]);
    
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required|string|min:6',
        
        ]);
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = auth()->user();
        }
        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }
        if (! $token = auth()->attempt($validator->validated()))
        {
            return response()->json(
                ['status'=>false,
                'message'=>trans('Inavalid email or password please try again'),
                'code'=>401],401);
        }
        $user = auth()->user();
        return $this->createNewToken($token);

    }

    public function logout() 
    {
        auth()->logout();
        return response()->json(['status'=>true,'message'=>trans('user successfully logout'),'code'=>200],200);
    }

    public function refresh() 
    {
        
        return $this->createNewToken(auth()->refresh());
    
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'status'=>true,
            'code'=>201,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data' => auth()->user()
        ],201);
    }

    public function userProfile()
    {
        return response()->json([
            'status'=>true,
            'message'=>trans('User profile have been shown successfuly'),
            'code'=>200,
            'data' => auth()->user()           
        ],200);
    }

    public function isValidToken(Request $request)
    {
        
            return response()->json([
                'status'=>true,
                'message'=>trans('app.valid'),
                'code'=>200,
                'data' => auth()->check()           
            ],200); 

    }

    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->id());
        $validator = Validator::make($request->all(), [

            'name' => 'required|regex:/^[(a-zA-Z\s)\p{L}]+$/u|max:50',
            'lastname' => 'required|regex:/^[(a-zA-Z\s)\p{L}]+$/u|max:50',
            'password'=>'required|min:6|max:12|regex:/^(?=.*[a-zA-Z\p{L}])(?=.*\d).+$/u',
            'mobile_no' => ['required', 'min:9','max:9','regex:/^[- +()]*[0-9][- +()0-9]*$/' ,'unique:users,mobile_no,'.$user->id],
            'email' => ['required','string', 'email','unique:users,email,'.$user->id],
            'gender'=>'required',
            'birth_date'=>'required',
        
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }

        $user->name=$request->input('name');
        $user->lastname=$request->input('lastname');
        $user->password=bcrypt($request->input('password'));
        $user->mobile_no=$request->input('mobile_no');
        $user->email=$request->input('email');
        $user->gender=$request->input('gender');
        $user->birth_date=$request->input('birth_date');

        $user->save();
        return response()->json([
            'status'=>true,
            'message'=>'user data updated successfully',
            'code'=>200,
            'data'=>$user,
        ],200);
    }

    

    

}
