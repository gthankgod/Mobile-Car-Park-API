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
        $users = User::query()->whereNotIn('id', [$this->user->id])->paginate();

        return response()->json($users);
    }


}
