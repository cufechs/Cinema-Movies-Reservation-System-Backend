<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <=20; $i++)
            if(DB::table('users')->where('id', $i)->value('role') == 'customer')
                factory(App\Models\Customer::class)->create(['user_id' => $i]);
    }
}
