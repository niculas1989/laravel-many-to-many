<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Post;
use App\Models\Category;
use Faker\Generator as Faker;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $c_ids = Category::pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $post = new Post();
            $post->category_id = Arr::random($c_ids);
            $post->title = $faker->text(20);
            $post->content = $faker->paragraphs(2, true);
            $post->image = $faker->imageUrl(300, 300);
            $post->is_published = 1;
            $post->slug = Str::slug($post->title, '-');
            $post->save();
        }
    }
}
