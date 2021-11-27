<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();
        for($i=0; $i<10; $i++){
            DB::table('barangs')->insert([
                'nama' => $faker->unique()->numerify('AMPLOP ## COKLAT JAYA'),
                'kode' => $faker->unique()->numerify('ATK-######'),
                'lokasi' => $faker->unique()->numerify('L1-#####'),
                'qty' => $faker->numberBetween(5, 20),
                'uom' => 'Pak',
            ]);
        }
    }
}
