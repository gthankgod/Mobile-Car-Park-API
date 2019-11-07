<?php


namespace App\Http\Controllers\API;


use App\User;

class AdminUsersController
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function index()
    {
        $users = User::query()->whereNotIn('id', [$this->user->id])->get();

        return response()->json(['data' => $users]);
    }

    public function show(User $user_id)
    {
        return response()->json(['data' => $user_id]);
    }
}
