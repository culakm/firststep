<?php

namespace Database\Seeders;

use App\Models\Tag;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seeder generuje nahodnost , toto sa pri zlozitejsich veciach robi vo factories
        //natvrdo zadane polozky pola
        $tags = ['tag_1','tag_2','tag_3','tag_4','tag_5'];
        // faker, musi byt pouzite use Faker\Factory as Faker;
        $faker = Faker::create();

        // nahodne generovane slova
        for ($i=0; $i < 5; $i++) { 
            $tags[] = $faker->word();
        }

        $tags_real = ['Sport', 'Sience', 'Politics'];

        $tags = array_merge($tags, $tags_real);
        //pole do collection
        $tags_to_save = collect($tags);

        // ulozenie modelu do DB
        $tags_to_save->each(function ($tag_name_z_tags_to_save){
            $tag = new Tag();
            $tag->name = $tag_name_z_tags_to_save;
            $tag->save();
        });
    }
}
