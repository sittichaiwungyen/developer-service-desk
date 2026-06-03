<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'ฮาร์ดแวร์', 'code' => 'HW', 'description' => 'อุปกรณ์คอมพิวเตอร์และอุปกรณ์ประกอบ'],
            ['name' => 'ซอฟต์แวร์', 'code' => 'SW', 'description' => 'โปรแกรมและระบบงานต่างๆ'],
            ['name' => 'เครือข่าย', 'code' => 'NET', 'description' => 'อินเทอร์เน็ต เครือข่าย และการเชื่อมต่อ'],
            ['name' => 'บัญชีผู้ใช้', 'code' => 'ACC', 'description' => 'สิทธิ์เข้าใช้งานและข้อมูลผู้ใช้'],
            ['name' => 'อุปกรณ์ต่อพ่วง', 'code' => 'PER', 'description' => 'เครื่องพิมพ์ สแกนเนอร์ และอุปกรณ์เสริม'],
        ];

        foreach ($categories as $category) {
            $record = Category::firstOrCreate(
                ['code' => $category['code']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );

            if (! $record->uuid) {
                $record->forceFill(['uuid' => (string) Str::uuid()])->save();
            }

            $changes = [];

            if ($record->name !== $category['name']) {
                $changes['name'] = $category['name'];
            }

            if ($record->description !== $category['description']) {
                $changes['description'] = $category['description'];
            }

            if ($changes !== []) {
                $record->forceFill($changes)->save();
            }
        }
    }
}