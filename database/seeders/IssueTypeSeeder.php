<?php

namespace Database\Seeders;

use App\Models\IssueType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IssueTypeSeeder extends Seeder
{
    public function run(): void
    {
        $issueTypes = [
            ['name' => 'ใช้งานไม่ได้', 'code' => 'ISS-FAIL', 'description' => 'ระบบหรืออุปกรณ์ไม่สามารถใช้งานได้'],
            ['name' => 'ช้า / ค้าง', 'code' => 'ISS-SLOW', 'description' => 'การทำงานช้าหรือค้างเป็นระยะ'],
            ['name' => 'ข้อมูลผิดพลาด', 'code' => 'ISS-DATA', 'description' => 'ข้อมูลไม่ถูกต้องหรือแสดงผลผิด'],
            ['name' => 'ติดตั้ง / ตั้งค่า', 'code' => 'ISS-SETUP', 'description' => 'ต้องการติดตั้งหรือปรับค่าระบบ'],
            ['name' => 'ขอสิทธิ์ / เปิดใช้งาน', 'code' => 'ISS-ACCESS', 'description' => 'ขอเพิ่มสิทธิ์หรือเปิดใช้งานบางอย่าง'],
            ['name' => 'แจ้งเตือนผิดปกติ', 'code' => 'ISS-ALERT', 'description' => 'ได้รับข้อความแจ้งเตือนหรือ error message'],
        ];

        foreach ($issueTypes as $issueType) {
            $record = IssueType::firstOrCreate(
                ['code' => $issueType['code']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $issueType['name'],
                    'description' => $issueType['description'],
                ]
            );

            if (! $record->uuid) {
                $record->forceFill(['uuid' => (string) Str::uuid()])->save();
            }

            $changes = [];

            if ($record->name !== $issueType['name']) {
                $changes['name'] = $issueType['name'];
            }

            if ($record->description !== $issueType['description']) {
                $changes['description'] = $issueType['description'];
            }

            if ($changes !== []) {
                $record->forceFill($changes)->save();
            }
        }
    }
}