<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        //$functions = ['Manager', 'Boss', 'Finance analyst', 'Executive Manager', 'Customer service', 'Customer', 'Field technician'];
        $functions = ['Manager', 'Employee', 'Customer', 'Boss', 'Finance analyst', 'Customer service'];
        $roles = [];

        for($i=1; $i <= count($functions); $i++){
            $roles[] = [
                'id' => $i,
                'role_name' => $functions[$i - 1]
            ];
        }

        DB::table('roles')->insert($roles);
    }
}
