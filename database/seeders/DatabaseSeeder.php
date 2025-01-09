<?php

namespace Database\Seeders;

use App\Models\Item;
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
        $this->createItems();

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
        User::query()->create([
            "name" => $name,
            "email" => "firdausi@hanscadi.ng",
            "password" => Hash::make('12345678'),
            "role"=> 'keeper',
            "email_verified_at" => Carbon::now(),
        ]);
    }


    public function createItems(){
        Item::create(['name'=> 'Biscuit', 'quantity'=> 10 , 'default_limit'=>2]);
        Item::create(['name'=> 'Milo', 'quantity'=> 10, 'default_limit'=>1]);
        Item::create(['name'=> 'Milk', 'quantity'=> 10, 'default_limit'=>1]);
        Item::create(['name'=> 'Salt']);
    }
}
