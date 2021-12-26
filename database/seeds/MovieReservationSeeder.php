<?php

use Illuminate\Database\Seeder;

class MovieReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\MovieReservation::class, 5)->create();
    }
}
