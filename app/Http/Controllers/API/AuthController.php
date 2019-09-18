<?php

namespace App\Http\Controllers\API;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function foo\func;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';
    public function customRegister(Request $req){
        $req->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        return response()->json([
            'user' => $user,
            'status' => 201
        ]);
    }

    public function myLogin(Request $request)
    {
        // Check if a user with the specified email exists
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }
        // If a user with the email was found - check if the specified password
        // belongs to this user
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
           return $this->sendLoginResponse($request);
        }

    }

    protected function sendLoginResponse(Request $request)
    {
        /*
         {
             **OPTIONAL Send an internal API request to get an access token
            $client = DB::table('oauth_clients')
                ->where('password_client', true)
                ->first();

                 Make sure a Password Client exists in the DB
            if (!$client) {
                return response()->json([
                    'message' => 'Laravel Passport is not setup properly.',
                    'status' => 500
                ], 500);
            }
        }
        */
        $data = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->email,
            'password' => $request->password,
        ];

        return $this->passport($data);
    }

    protected function passport($data)
    {
        $request = Request::create(config('services.passport.login_endpoint'), 'POST', $data);
        $response = app()->handle($request);
        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }
        // Get the data from the response
        $data = json_decode($response->getContent());
        Auth::user()->withAccessToken($data->access_token);
        return response()->json([
            'redirect' => route('home'),
            'user' => \auth()->user(),
            'access_token' => \auth()->user()->token(),
            'status' =>$response->getStatusCode(),
        ]);
    }

    public function myLogout(){
        auth()->user()->tokens->each(function ($token, $key){
            $token->delete();
        });
        return response()->json([
            'message' => 'Successfuly logged out!',
            'redirect' => route('login'),
        ], 200);
    }

    # dev.to
    public function devToLogin(Request $req){
        $user = User::where('email', $req->email)->first();
        if($user){
            if(Hash::check($req->password, $user->password)){
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json([
                   'user' => $user,
                   'token' => $token,
                   'message' => 'Logged in!',
                   'status' => 200
                ]);
            }else{
                return response()->json([
                    'message' => 'Wrong Password',
                    'status' => 422
                ]);
            }
        }else{
            return response()->json([
                'message' => 'Wrong Email',
                'status' => 422
            ]);
        }
    }

    # Andre Madarang
    public function andreLogin(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' =>config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                ]
            ]);
            return response()->json([
                'data' => $response->getBody(),
                'status' => $response->getStatusCode()
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    #hamzali
    public function hamzaliLogin(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('Laravel Password Grant Client')->accessToken;
            return response()->json([
                'token' => $token,
                'user' => auth()->user(),
            ], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }
}
