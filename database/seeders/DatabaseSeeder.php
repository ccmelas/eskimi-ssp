<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Image;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Campaign::factory(10)
            ->has(Image::factory()->count(3))
            ->create();
    }
}
