<?php

use Faker\Generator as Faker;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $tag_names = [
            'Fissa', 'Portatile', 'Schermo Integrato', 'FullHD', '4K', '1TB SSD'
        ];

        foreach ($tag_names as $tag) {
            $new_tag = new Tag();
            $new_tag->label = $tag;
            $new_tag->color = $faker->hexColor();
            $new_tag->save();
        }
    }
}
