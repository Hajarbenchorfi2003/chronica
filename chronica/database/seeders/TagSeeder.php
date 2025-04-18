<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = ['PHP', 'Laravel', 'JavaScript', 'CSS', 'Vue.js'];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => \Str::slug($tag),
            ]);
        }
    }
}
