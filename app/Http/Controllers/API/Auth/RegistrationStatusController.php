<?php


namespace App\Http\Controllers\API\Auth;


use App\Classes\Helper;
use App\User;
use Illuminate\Http\Request;

class RegistrationStatusController
{

    /**
     * Check if the given phone number is registered on the platform
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withPhone(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'phone:NG'],
        ]);

        $phone = Helper::formatPhoneNumber($data['phone']);

        $status = User::query()->where('phone', $phone)->exists();

        return response()->json([
            'registered' => $status,
        ]);
    }
}
