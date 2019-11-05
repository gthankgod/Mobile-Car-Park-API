<?php


namespace App\Http\Controllers\API\Auth;


use App\Classes\Helper;
use App\Rules\ProcessedOTPAndPhone;
use App\Rules\RegisteredPhonNumber;
use App\Rules\UnregisteredPhone;
use App\OTP;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OTPController
{

    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'phone:NG'],
        ]);

        $phone = Helper::formatPhoneNumber($request->input('phone'));

        $otp = $this->generateAndSendOTP($phone);

        $message = "OTP has been sent to {$phone}";

        $user = User::query()->where('phone', $phone)->where('role', 'user')->first();

        if (! $user) {
            // The number is not registered
            OTP::query()->updateOrCreate(['phone' => $phone], ['otp' => $otp]);
            return response()->json([
                'message' => $message,
                'registered' => false,
            ]);
        }


        // Phone is registered
        $user->update(['otp' => $otp]);

        return response()->json([
            'message' => $message,
            'registered' => true,
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'phone:NG'],
            'otp' => ['required', 'string']
        ]);

        $phone = Helper::formatPhoneNumber($data['phone']);

        // Check if phone number is registered
        $user = User::query()->where('phone', $phone)->where('role', 'user')->first();
        if (! $user) {
            // Number is not registered
            return $this->handleUnregisteredUser($phone, $data['otp']);
        }

        if ($user->otp === $data['otp']) {
            $token = auth()->login($user);

            if (! $token) {
                // Should not happen, but check if token has not been created
                return response()->json(['message' => "An error was encountered"], 500);
            }

            return response()->json([
                'massage' => "OTP is valid, login successful.",
                "registered" => true,
                'data' => [
                    'access_token' => $token,
                    'expires_in' => auth()->factory()->getTTL() * 60
                ],
            ]);
        }

        //OTP is not valid
        return $this->invalidOTPResponse(true);
    }

    private function handleUnregisteredUser(string $phone, string $otp)
    {
        // Check that the OTP is correct
        if (OTP::query()->where('phone', $phone)->where('otp', $otp)->exists()) {
            return response()->json([
                'message' => "OTP is valid",
                "registered" => false,
                "data" => null,
            ]);
        }

        // OTP is not valid
        return $this->invalidOTPResponse(false);
    }

    private function invalidOTPResponse(bool $registered)
    {
        return response()->json([
            'message' => "OTP is not correct",
            "registered" => $registered,
        ], 400);
    }

    private function generateAndSendOTP(string $phone)
    {
        try {
           $otp =  random_int(1000, 9999);
        } catch (\Exception $e) {
           $otp = rand(1000, 9999);
        }
        // TODO Send ana SMS to the phone number
        // for now we'll use a static OTP
        $otp = 1234;

        return $otp;
    }
}
