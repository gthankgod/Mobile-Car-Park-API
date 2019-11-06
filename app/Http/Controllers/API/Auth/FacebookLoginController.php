<?php


namespace App\Http\Controllers\API\Auth;


use App\Classes\Helper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FacebookLoginController
{
    private $message = "User Account Updated";

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => ['nullable', 'email'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => ['nullable'],
            'fb_id' => ['required', 'string'],
        ]);

        $data['phone'] = Helper::formatPhoneNumber($data['phone']);
//        $data['password']  = Hash::make(Str::random(8));

        try {
            $user = $this->findOrCreateUser($data);
            $token = auth()->login($user);

            return response()->json([
                'message' => $this->message,
                'data' => [
                    'access_token' => $token,
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ]);
        } catch (\Exception $e) {
            Helper::logException($e);
            return response()->json([
                'message' => $e->getMessage(),
            ], 501);
        }

    }

    private function findOrCreateUser(array $data)
    {
        $user = User::query()->where('fb_id', $data['fb_id'])->first();

        if ($user) {
            // check if email from fb and this email are the same
            // Remove email and phone number, to avoid potential conflicts
            Arr::forget($data, ['email', 'phone']);

            $user->update($data);
            return $user;
        }

        // User with FB ID does not exist, try finding by email
        if ($user = User::query()->where('email', $data['email'])->first()) {
            // Don't update phone number, to avoid conflicts
            Arr::forget($data, 'phone');

            $user->update($data);
            return $user;
        }

        // Try searching by phone number
        if ($user = User::query()->where('phone', $data['email'])->first()) {
            // Don't update email, to avoid conflicts
            Arr::forget($data, 'email');

            $user->update($data);
            return $user;
        }

        // No match was found, so create a new user
        $this->message = "User account created";
        $data['password'] = Hash::make(Str::random(8));

        return User::query()->create($data);
    }

}
