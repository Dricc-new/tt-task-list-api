<?php

namespace Database\Seeders;

use App\Models\Keyword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keywords = [
            'Urgente',
            'Trabajo',
            'Personal',
            'Estudio',
            'Importante',
        ];

        foreach ($keywords as $name) {
            Keyword::firstOrCreate(['name' => $name]);
        }
    }
}
