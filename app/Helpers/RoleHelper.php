<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;

class RoleHelper {

    public function hasRole($userID, $roleName){
        $role = DB::table('roles')
        ->where('userID', $userID)
        ->value('roleDescription');

        $role = ($role === $roleName) ? true : false;

        return $role;
    }
}

?>