<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            ['name' => 'คอมพิวเตอร์ / โน้ตบุ๊ก', 'code' => 'HW-COMPUTER', 'description' => 'ปัญหากับเครื่องคอมพิวเตอร์หรือโน้ตบุ๊ก'],
            ['name' => 'จอภาพ / อุปกรณ์แสดงผล', 'code' => 'HW-DISPLAY', 'description' => 'หน้าจอหรืออุปกรณ์แสดงผลผิดปกติ'],
            ['name' => 'โปรแกรมงานประจำ', 'code' => 'SW-APP', 'description' => 'โปรแกรมหรือระบบงานหลักของหน่วยงาน'],
            ['name' => 'ติดตั้ง / อัปเดตโปรแกรม', 'code' => 'SW-SETUP', 'description' => 'การติดตั้งหรืออัปเดตซอฟต์แวร์'],
            ['name' => 'อินเทอร์เน็ต', 'code' => 'NET-INTERNET', 'description' => 'ปัญหาเชื่อมต่ออินเทอร์เน็ต'],
            ['name' => 'LAN / Wi-Fi', 'code' => 'NET-LANWIFI', 'description' => 'ปัญหาเครือข่ายภายในหรือไร้สาย'],
            ['name' => 'ล็อกอิน / สิทธิ์ใช้งาน', 'code' => 'ACC-ACCESS', 'description' => 'การเข้าใช้งานหรือสิทธิ์ในระบบ'],
            ['name' => 'เครื่องพิมพ์', 'code' => 'PER-PRINTER', 'description' => 'ปัญหาเครื่องพิมพ์และงานพิมพ์'],
        ];

        foreach ($subcategories as $subcategory) {
            $record = Subcategory::firstOrCreate(
                ['code' => $subcategory['code']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $subcategory['name'],
                    'description' => $subcategory['description'],
                ]
            );

            if (! $record->uuid) {
                $record->forceFill(['uuid' => (string) Str::uuid()])->save();
            }

            $changes = [];

            if ($record->name !== $subcategory['name']) {
                $changes['name'] = $subcategory['name'];
            }

            if ($record->description !== $subcategory['description']) {
                $changes['description'] = $subcategory['description'];
            }

            if ($changes !== []) {
                $record->forceFill($changes)->save();
            }
        }
    }
}