<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['label' => 'PS4', 'color' => 'info'],
            ['label' => 'XBOX 360', 'color' => 'success'],
            ['label' => 'XBOX S', 'color' => 'warning'],
            ['label' => 'Nintendo-Switch', 'color' => 'danger'],
            ['label' => 'PSP', 'color' => 'secondary']
        ];

        foreach ($categories as $category) {
            $c = new Category();
            $c->label = $category['label'];
            $c->color = $category['color'];
            $c->save();
        }
    }
}
