<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Users that are going to be added
        $users = [
            "jane@example.org",
            "jim@example.org",
            "john@example.org",
        ];
        $password = Hash::make("Secret!");
        $i = 1;

        foreach($users as $email){
            DB::table("users")->insert(
                [
                    "name" => explode("@", $email)[0],
                    "email" => $email,
                    "password" => $password,
                    "role" => $i,
                ]
            );

            // We only want the first user to have a manager role
            if($i === 1){
                $i = 0;
            }
        }
    }
}
