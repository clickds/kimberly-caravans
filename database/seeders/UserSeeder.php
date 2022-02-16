<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = Str::random(10);
        $user->email = Str::random(10) . '@localdev';
        $user->email_verified_at = new \DateTime();
        $user->password = Hash::make('123456');
        $user->save();
    }
}
