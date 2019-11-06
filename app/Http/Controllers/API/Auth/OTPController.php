<?php


namespace App\Http\Controllers\API\Auth;


use App\Classes\Helper;;
use App\OTP;
use App\Rules\UnregisteredPhone;
use App\User;
use Illuminate\Http\Request;

class OTPController
{

    public function send(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'phone:NG', new UnregisteredPhone],
        ]);

        $phone = Helper::formatPhoneNumber($request->input('phone'));

        $otp = $this->generateAndSendOTP($phone);

        $message = "OTP has been sent to {$phone}";

        $user = User::query()->where('phone', $phone)->where('role', 'user')->first();


        OTP::query()->updateOrCreate(['phone' => $phone], ['otp' => $otp]);
        return response()->json([
            'message' => $message,
        ]);

    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'phone:NG'],
            'otp' => ['required', 'string']
        ]);

        $phone = Helper::formatPhoneNumber($data['phone']);

        $record = OTP::query()->where('phone', $phone)->first();
        if (!$record) {
            return response()->json([
                'message' => "Request for an OTP to be sent to {$phone} first, before attempting to verify it",
            ], 400);
        }

        if ($record->otp != $data['otp']) {
            return response()->json([
                'message' => "The OTP is not correct",
            ], 400);
        }

        //OTP is Valid
        $record->verified = true;
        $record->update();

        return response()->json([
            'message' => "OTP is valid",
        ]);


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
