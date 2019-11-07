<?php

namespace App\Http\Controllers\API\Auth;

use App\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Rules\RegisteredPhonNumber;
use App\OTP;
use App\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Login a client/user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function user(Request $request)
   {
       $data = $request->validate([
           'phone' => ['required', new RegisteredPhonNumber],
           'password' => ['required', 'string'],
       ]);

       $data['phone'] = Helper::formatPhoneNumber($data['phone']);

       $user = User::where('phone', $data['phone'])->where('role', 'user')->first();

       // Check password
       if (
           ! $user
            || !Hash::check($data['password'], $user->password)
       ) {
           return  response()->json([
               'message' => "Invalid phone number/password combination.",
           ], 400);
       }

       $token = auth()->login($user);

       if (! $token) {
           return response()->json(['message' => 'An error ws encountered.'], 500);
       }

        return $this->createResponse($token, $user);
   }

    /**
     * Login admin and partners
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function adminAndPartner(Request $request)
   {
       $data = $request->validate([
          'email' => ['required', 'exists:users'],
          'password' => ['required'],
       ]);

       $user = User::query()->where('email', $data['email'])->whereIn('role' , ['admin', 'partner'])
           ->first();

       if (
           !$user
            || !(Hash::check($data['password'], $user->password))
       ) {
            return response()->json(['message' => 'Incorrect email/password combination.'], 401);
       }

       if (! $token = auth()->login($user)) {
           return response()->json(['message' => 'An error was encountered.'], 500);
       }

       return $this->createResponse($token, $user);
   }

    /**
     * Create a JSON response for successful login
     * @param string $token
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
   private function createResponse(string $token, User $user)
   {
       event(new Login('api', $user, false));

       return response()->json([
           'message' => 'Login successful.',
           'data' => [
               'access_token' => $token,
               'expires_in' => auth()->factory()->getTTL() * 60,
               'user' => $user,
           ]
       ]);
   }
}
