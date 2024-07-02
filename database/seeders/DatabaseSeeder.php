<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->createAdminUser();
        $this->createManagerUser();
        $this->createKeeperUser();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }


    public function createAdminUser(){
        User::query()->create([
            "name" => "admin",
            "email" => "admin@hanscadi.ng",
            "password" => Hash::make('HansAdmin'),
            "role"=> 'admin',
            "email_verified_at" => Carbon::now(),
        ]);
    }

    public function createManagerUser(){
        $name = "Ahmed Mahmood";
        Employee::query()->create([
            "name" => $name
        ]);
        User::query()->create([
            "name" => $name,
            "email" => "ahmed.mahmood@hanscadi.ng",
            "password" => Hash::make('HansAhmed'),
            "role"=> 'manager',
            "email_verified_at" => Carbon::now(),
        ]);
    }

    public function createKeeperUser(){
        $name = "Nana Firdausi";
        Employee::query()->create([
            "name" => $name
        ]);
        User::query()->create([
            "name" => $name,
            "email" => "firdausi@hanscadi.com",
            "password" => Hash::make('12345678'),
            "role"=> 'keeper',
            "email_verified_at" => Carbon::now(),
        ]);
    }
}
