<?php

use App\User;
use App\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class IndexSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();

        $id = User::insertGetId([
            'name' => 'Asef',
            'email' => 'mochamadasef99@gmail.com',
            'password' => Hash::make('12345678'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        UserDetail::insert([
            'user_id' => $id,
            'address' => 'Indramayu',
            'phone' => '089514291012',
            'hobby' => 'Madang',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        for($i = 0; $i < 25; $i++) {
            $id = User::insertGetId([
                'name'      =>  $faker->name,
                'email'     =>  $faker->unique()->safeEmail,
                'password'  =>  Hash::make('password'),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            UserDetail::insert([
                'user_id' => $id,
                'address' => Str::random(10),
                'phone' => Str::random(10),
                'hobby' => Str::random(10),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        Artisan::call('passport:install');
    }
}
