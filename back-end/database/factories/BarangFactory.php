<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class BarangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Barang::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama' => $this->faker->unique()->numerify('AMPLOP ## COKLAT JAYA'),
            'kode' => $this->faker->unique()->numerify('ATK-######'),
            'lokasi' => $this->faker->unique()->numerify('L1-#####'),
            'qty' => $this->faker->numberBetween(5, 20),
            'uom' => 'Pak',
        ];
    }
}
