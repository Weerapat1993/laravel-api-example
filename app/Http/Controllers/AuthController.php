<?php

/**
 * @api {post} /login POST Auth Login
 * @apiSampleRequest /api/login
 * @apiParam {String} email  Email
 * @apiParam {String} password     Password
 * @apiName authLogin
 * @apiGroup Authentication
 */

/**
 * @api {post} /register POST Auth Register
 * @apiSampleRequest /api/register
 * @apiParam {String} name  Name
 * @apiParam {String} email  Email
 * @apiParam {String} [password]     Password
 * @apiName authRegister
 * @apiGroup Authentication
 */

 /**
 * @api {post} /recover POST Auth Recover
 * @apiSampleRequest /api/recover
 * @apiParam {String} email  Email
 * @apiName authRecover
 * @apiGroup Authentication
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator, DB, Hash, Mail, Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'min:6|max:255',
            'avatar' => '',
        ];
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validator
        $credentials = $request->only('name', 'email', 'password', 'avatar');
        $validator = Validator::make($credentials, $this->rules);
        if($validator->fails()) {
            return $this->getFailure(400, $validator->messages());
        }
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $avatar = $request->avatar;

        $check_user = User::where('email', '=', $email);
        if(!$check_user->count()) {
            User::create([
                'name' => $name, 
                'email' => $email, 
                'password' => Hash::make($password),
                'avatar' => $avatar,
            ]);
        }

        return $this->login($request);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return $this->getFailure(400, $validator->messages());
        }
        try {
            $token = JWTAuth::attempt($credentials);
            // attempt to verify the credentials and create a token for the user
            if (!$token) {
                return $this->getFailure(401, 'We cant find an account with this credentials.');
            }
            $user = User::where('email', '=', $request->email)->firstOrFail();
            return $this->getSuccess(500, [
                'token' => $token,
                'user' => $user,
            ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->getFailure(500, 'Failed to login, please try again.');
        }
    }
    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);
        try {
            JWTAuth::invalidate($request->input('token'));
            return $this->getSuccess(200, "You have successfully logged out.");
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->getFailure(500, 'Failed to logout, please try again.');
        }
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email'=> $error_message]], 401);
        }
        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }
        return response()->json([
            'success' => true, 'data'=> ['message'=> 'A reset email has been sent! Please check your email.']
        ]);
    }

    public function getAuthUser(Request $request){
        $user = JWTAuth::toUser($request->header('Authorization'));
        // $user = JWTAuth::toUser($request->get('token'));
        return response()->json([
            'data' => $user,
            'code' => 200,
            'status' => 'OK',
        ]);
    }
}