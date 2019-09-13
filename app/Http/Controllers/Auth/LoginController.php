<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
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

        $credentials = request()->only('email', 'password');
        if(auth()->attempt($credentials)){
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
            auth()->user()->withAccessToken($data->access_token);
            return redirect()->intended($this->redirectPath());
//            return response()->json([
//                'message' => 'Returned using Auth::user',
//                'user' => auth()->user(),
//                'access_token' => auth()->user()->token(),
//                'status' =>$response->getStatusCode(),
//            ]);
        }

    }



}
