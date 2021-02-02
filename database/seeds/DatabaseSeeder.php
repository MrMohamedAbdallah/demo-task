<?php

use App\User;
use App\Fav;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed "favs" table

        $users = User::get();
        $faker = Faker\Factory::create();

        // Add 10 favs to each user
        foreach($users as $user){

            for($i = 0; $i < 10; $i++){
                $fav = new Fav();
                
                $fav->session_id = $faker->randomNumber(8);
                $fav->fav_status = random_int(0, 10) % 2 == 0;
                $fav->user_id = $user->id;

                $fav->save();
            }
        }
    }
}
