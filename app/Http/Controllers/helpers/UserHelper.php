<?php
namespace App\Http\Controllers\helpers;

use App\User;

class UserHelper
{
    public function fetchUsers($request)
    {
        $user_group = isset($request->user_group) ? $request->user_group : "customer";
        $user_group_ids = $user_group === "customer" ? [1, 2] : [3];
        // ::Review::
        $users = User::with("permissions")->whereIn("user_group_id", $user_group_ids)->get();

        return $users;
    }
}
