<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Emergency Room', 'code' => 'ER'],
            ['name' => 'Outpatient Department', 'code' => 'OPD'],
            ['name' => 'Inpatient Department', 'code' => 'IPD'],
            ['name' => 'Laboratory', 'code' => 'LAB'],
            ['name' => 'Pharmacy', 'code' => 'PHARM'],
            ['name' => 'Radiology', 'code' => 'RAD'],
            ['name' => 'IT Department', 'code' => 'IT'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Medical Records', 'code' => 'MR'],
        ];

        foreach ($departments as $department) {
            $record = Department::firstOrCreate(
                ['code' => $department['code']],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $department['name'],
                ]
            );

            if (! $record->uuid) {
                $record->forceFill(['uuid' => (string) Str::uuid()])->save();
            }

            if ($record->name !== $department['name']) {
                $record->forceFill(['name' => $department['name']])->save();
            }
        }
    }
}
