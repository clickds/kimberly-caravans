<?php

namespace Database\Seeders;

use App\Models\News;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $generator)
    {

        for ($i = 1; $i <= 50; $i++) {

            $title = implode(' ',$generator->words(5));
            $content = '<p>' . implode('</p><p>', $generator->sentences(5)) . '</p>';

            $item = new News;
            $item->title = $title;
            $item->slug = 'test-news-item-' . $i;
            $item->content = $content;
            $item->published = true;
            $item->author_id = 1;
            $item->save();
        }
    }
}
