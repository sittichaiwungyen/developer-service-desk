<?php

namespace Database\Seeders;

use App\Models\AffectedSystem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AffectedSystemSeeder extends Seeder
{
    public function run(): void
    {
        $systems = [
            ['name' => 'HIS', 'code' => 'SYS-HIS', 'description' => 'Hospital Information System'],
            ['name' => 'ERP', 'code' => 'SYS-ERP', 'description' => 'ระบบบริหารงานองค์กร'],
            ['name' => 'LIS', 'code' => 'SYS-LIS', 'description' => 'Laboratory Information System'],
            ['name' => 'PACS', 'code' => 'SYS-PACS', 'description' => 'ระบบภาพทางการแพทย์'],
            ['name' => 'Internet', 'code' => 'SYS-INTERNET', 'description' => 'อินเทอร์เน็ตและการเชื่อมต่อภายนอก'],
            ['name' => 'Network', 'code' => 'SYS-NETWORK', 'description' => 'เครือข่ายภายในองค์กร'],
            ['name' => 'E-Mail', 'code' => 'SYS-MAIL', 'description' => 'ระบบอีเมลขององค์กร'],
            ['name' => 'CCTV', 'code' => 'SYS-CCTV', 'description' => 'ระบบกล้องวงจรปิด'],
        ];

        foreach ($systems as $system) {
            $record = AffectedSystem::firstOrCreate(
                ['code' => $system['code']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $system['name'],
                    'description' => $system['description'],
                ]
            );

            if (! $record->uuid) {
                $record->forceFill(['uuid' => (string) Str::uuid()])->save();
            }

            $changes = [];

            if ($record->name !== $system['name']) {
                $changes['name'] = $system['name'];
            }

            if ($record->description !== $system['description']) {
                $changes['description'] = $system['description'];
            }

            if ($changes !== []) {
                $record->forceFill($changes)->save();
            }
        }
    }
}