<?php

use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{

    public function run()
    {
        $path = storage_path('translations.sql');
        DB::unprepared(file_get_contents($path));
    }
}
