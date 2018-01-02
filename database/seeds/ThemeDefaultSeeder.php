<?php

use Illuminate\Database\Seeder;

class ThemeDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('themes')->insert([
             [
                 'id' => 1000,
                 'name' => 'buckyDrop',
                 'features'=>"Default template",
                 'default'=>1,
                 'created_at'=>date("Y-m-d H:i:s"),
                 'updated_at'=>date("Y-m-d H:i:s")
             ],
        ]);
    }
}
