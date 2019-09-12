<?php

namespace App\Http\Controllers\API;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
        $user = User::whereEmail(request('email'))->first();

        if (!$user) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }
        // If a user with the email was found - check if the specified password
        // belongs to this user
        if (!Hash::check(request('password'), $user->password)) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }
//        **OPTIONAL Send an internal API request to get an access token
//        $client = DB::table('oauth_clients')
//            ->where('password_client', true)
//            ->first();

        // Make sure a Password Client exists in the DB
//        if (!$client) {
//            return response()->json([
//                'message' => 'Laravel Passport is not setup properly.',
//                'status' => 500
//            ], 500);
//        }

        $data = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => request('email'),
            'password' => request('password'),
        ];

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
        return response()->json([
            'user' => $user,
            'access_token' => $data->access_token,
            'status' =>$response->getStatusCode(),
        ]);
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
        $http = new Client;
        try {
            $response = $http->post('http://localhost:8000/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' =>'2', #config('services.passport.client_id'),
                    'client_secret' =>'5PUp7tmht6n7GR9em4A7ohDgC105gEt1jJSwvd5Q',#config('services.passport.client_secret'),
                    'username' => $request->email,
                    'password' => $request->password,
                ]
            ]);
            return response()->json([
                'data' => $response->getBody(),
                'status' => $response->getStatusCode()
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
}
