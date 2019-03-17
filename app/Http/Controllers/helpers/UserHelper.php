<?php
namespace App\Http\Controllers\helpers;

use App\User;
use App\UserPermission;

class UserHelper
{
    public function fetchUsers($request)
    {
        $user_group = isset($request->user_group) ? $request->user_group : "customer";
        $user_group_ids = $user_group === "customer" ? [1, 2] : [3];
        // ::Review::
        $users = User::with("permissions")->whereIn("user_group_id", $user_group_ids)->get();
        if ($user_group === "customer") {

            return $users;
        }

        foreach ($users as $user) {
            $user = self::addAccessLevel($user);

        }
        return $users;
    }

    public function addAccessLevel($user)
    {
        $user['accessOrders'] = UserPermission::where("user_id", $user->user_id)->where("permission_id", 2)->first() !== null;
        $user['accessProducts'] = UserPermission::where("user_id", $user->user_id)->where("permission_id", 3)->first() !== null;
        $user['accessSalesGroups'] = UserPermission::where("user_id", $user->user_id)->where("permission_id", 4)->first() !== null;
        $user['accessReports'] = UserPermission::where("user_id", $user->user_id)->where("permission_id", 5)->first() !== null;
        $user['accessAccounts'] = UserPermission::where("user_id", $user->user_id)->where("permission_id", 6)->first() !== null;

        return $user;

    }
}
