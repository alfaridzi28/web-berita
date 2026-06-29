<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Nasional',      'slug' => 'nasional',      'color' => '#EF4444'],
            ['name' => 'Internasional', 'slug' => 'internasional', 'color' => '#8B5CF6'],
            ['name' => 'Teknologi',     'slug' => 'teknologi',     'color' => '#3B82F6'],
            ['name' => 'Ekonomi',       'slug' => 'ekonomi',       'color' => '#10B981'],
            ['name' => 'Olahraga',      'slug' => 'olahraga',      'color' => '#F59E0B'],
            ['name' => 'Hiburan',       'slug' => 'hiburan',       'color' => '#EC4899'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $this->command->info('6 kategori berhasil dibuat.');
    }
}
