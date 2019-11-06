<?php

use App\Models\Auth\UserModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(UserModel $user)
    {
        $user->name = 'admin';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('123456');
        $user->save();

    }
}
