<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        DB::table('users')->insert([
            'name' => 'John doe',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

       /*
$table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->rememberToken();

       */
    }
}
