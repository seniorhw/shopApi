<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //创建用户
        $user = User::create([
            'name'=>'超级管理员',
            'email'=>'super@a.com',
            'password'=>bcrypt('123123')
        ]);
        //给用户分配角色
        $user->assignRole('superadmin');
    }
}
