<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $t = factory(App\Models\User::class, 5)->create();
//        var_dump($t); die();
//        factory(App\Models\User::class, 5)->make();
    }
}