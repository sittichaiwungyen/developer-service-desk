<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\AffectedSystem;
use App\Models\Department;
use App\Models\IssueType;
use App\Models\Location;
use App\Models\Position;
use App\Models\Ticket;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MasterController extends Controller
{
    public function index(string $module)
    {
        $config = $this->moduleConfig($module);
        $search = trim((string) request('search', ''));
        $status = request('status', 'active');
        $usage = request('usage', 'all');
        $departmentFilter = trim((string) request('department_id', 'all'));
        $positionFilter = trim((string) request('position_id', 'all'));
        $departmentId = $this->resolveUserFilterId($departmentFilter, Department::class);
        $positionId = $this->resolveUserFilterId($positionFilter, Position::class);

        $query = ($config['query'])();
        $query = $this->applyIndexFilters($module, $query, $search, $status, $usage, $departmentFilter, $positionFilter, $config);
        $records = $query->paginate(12)->withQueryString();

        $extraFilterData = [];

        if ($module === 'users') {
            $extraFilterData = [
                'departments' => $this->departmentOptions(),
                'positions' => $this->positionOptions(),
            ];
        }

        return view('master.modules.index', [
            'module' => $module,
            'title' => $config['title'],
            'subtitle' => $config['subtitle'],
            'icon' => $config['icon'],
            'columns' => $config['columns'],
            'records' => $records,
            'rows' => $records->getCollection()->values()->map(fn ($record, $index) => array_merge(
                $this->rowData($module, $record),
                ['order' => (($records->currentPage() - 1) * $records->perPage()) + $index + 1]
            ))->all(),
            'summaryCards' => $this->summaryCards($module),
            'createUrl' => route("master.{$module}.create"),
            'emptyText' => $config['emptyText'],
            'filters' => [
                'search' => $search,
                'status' => $status,
                'usage' => $usage,
                'department_id' => $departmentFilter,
                'position_id' => $positionFilter,
            ],
            'departmentFilterValue' => $this->resolveUserFilterLabel($departmentFilter, Department::class),
            'positionFilterValue' => $this->resolveUserFilterLabel($positionFilter, Position::class),
            'filterOptions' => $this->filterOptions($module),
        ] + $extraFilterData);
    }

    public function create(string $module)
    {
        $config = $this->moduleConfig($module);

        return view('master.modules.form', [
            'module' => $module,
            'title' => 'เพิ่ม'.$config['title'],
            'subtitle' => 'สร้างข้อมูลชุดใหม่สำหรับ'.$config['title'],
            'record' => null,
            'actionUrl' => route("master.{$module}.store"),
            'method' => 'POST',
            'departments' => $this->departmentOptions(),
            'positions' => $this->positionOptions(),
            'roles' => $this->roleOptions(),
            'permissions' => $this->permissionOptions(),
        ]);
    }

    public function store(Request $request, string $module)
    {
        $config = $this->moduleConfig($module);
        $validated = $this->validateData($request, $module);
        $record = $this->newRecord($module);

        $this->fillRecord($record, $module, $validated);
        $record->save();
        $this->syncRelations($record, $module, $validated);

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'บันทึกสำเร็จ',
            'text' => 'เพิ่ม'.$config['title'].'เรียบร้อยแล้ว',
        ]);

        return redirect()->route("master.{$module}");
    }

    public function edit(string $module, int $record)
    {
        $config = $this->moduleConfig($module);
        $record = $this->findRecord($module, $record);

        return view('master.modules.form', [
            'module' => $module,
            'title' => 'แก้ไข'.$config['title'],
            'subtitle' => 'ปรับปรุงข้อมูล'.$config['title'].'ที่มีอยู่',
            'record' => $record,
            'actionUrl' => route("master.{$module}.update", $record),
            'method' => 'PUT',
            'departments' => $this->departmentOptions(),
            'positions' => $this->positionOptions(),
            'roles' => $this->roleOptions(),
            'permissions' => $this->permissionOptions(),
        ]);
    }

    public function update(Request $request, string $module, int $record)
    {
        $config = $this->moduleConfig($module);
        $record = $this->findRecord($module, $record);
        $validated = $this->validateData($request, $module, $record);

        $this->fillRecord($record, $module, $validated);
        $record->save();
        $this->syncRelations($record, $module, $validated);

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'บันทึกสำเร็จ',
            'text' => 'แก้ไข'.$config['title'].'เรียบร้อยแล้ว',
        ]);

        return redirect()->route("master.{$module}");
    }

    public function destroy(Request $request, string $module, int $record)
    {
        $config = $this->moduleConfig($module);
        $record = $this->findRecord($module, $record);

        if ($module === 'users' && (int) $request->user()->id === (int) $record->id) {
            session()->flash('sweetalert', [
                'icon' => 'warning',
                'title' => 'ไม่สามารถลบได้',
                'text' => 'คุณไม่สามารถลบบัญชีของตัวเองได้',
            ]);

            return back();
        }

        $record->delete();

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'ลบสำเร็จ',
            'text' => 'ลบ'.$config['title'].'เรียบร้อยแล้ว',
        ]);

        return redirect()->route("master.{$module}");
    }

    private function moduleConfig(string $module): array
    {
        return match ($module) {
            'users' => [
                'title' => 'ผู้ใช้',
                'subtitle' => 'จัดการบัญชีผู้ใช้ สิทธิ์ และสังกัด',
                'icon' => 'users',
                'emptyText' => 'ยังไม่มีบัญชีผู้ใช้ในระบบ',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'email', 'label' => 'อีเมล'],
                    ['key' => 'roles', 'label' => 'Role'],
                    ['key' => 'department', 'label' => 'แผนก'],
                    ['key' => 'position', 'label' => 'ตำแหน่ง'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => User::query()->with(['roles', 'department', 'position'])->orderBy('name'),
                    'searchFields' => ['name', 'email', 'roles.name', 'department.name', 'position.name'],
                    'usageFilter' => 'roles',
            ],
            'departments' => [
                'title' => 'แผนก',
                'subtitle' => 'จัดการแผนกและหน่วยงานภายในโรงพยาบาล',
                'icon' => 'building',
                'emptyText' => 'ยังไม่มีข้อมูลแผนก',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => Department::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'users',
            ],
            'positions' => [
                'title' => 'ตำแหน่งงาน',
                'subtitle' => 'จัดการตำแหน่งงานของผู้ใช้งานในระบบ',
                'icon' => 'briefcase',
                'emptyText' => 'ยังไม่มีข้อมูลตำแหน่งงาน',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => Position::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'users',
            ],
            'locations' => [
                'title' => 'สถานที่',
                'subtitle' => 'จัดการสถานที่ที่ใช้บันทึกและติดตาม Ticket',
                'icon' => 'map',
                'emptyText' => 'ยังไม่มีข้อมูลสถานที่',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => Location::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'tickets',
            ],
            'categories' => [
                'title' => 'หมวดหมู่งาน',
                'subtitle' => 'จัดการหมวดหมู่งานหลักสำหรับ Ticket',
                'icon' => 'tag',
                'emptyText' => 'ยังไม่มีข้อมูลหมวดหมู่งาน',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => Category::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'tickets',
            ],
            'subcategories' => [
                'title' => 'หมวดงานย่อย',
                'subtitle' => 'จัดการหมวดงานย่อยสำหรับ Ticket',
                'icon' => 'tag',
                'emptyText' => 'ยังไม่มีข้อมูลหมวดงานย่อย',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => Subcategory::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'tickets',
            ],
            'issue_types' => [
                'title' => 'ประเภทปัญหา',
                'subtitle' => 'จัดการประเภทปัญหาที่ใช้คัดกรอง Ticket',
                'icon' => 'shield',
                'emptyText' => 'ยังไม่มีข้อมูลประเภทปัญหา',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => IssueType::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'tickets',
            ],
            'affected_systems' => [
                'title' => 'ระบบที่ได้รับผลกระทบ',
                'subtitle' => 'จัดการชื่อระบบที่ใช้ระบุผลกระทบ',
                'icon' => 'monitor',
                'emptyText' => 'ยังไม่มีข้อมูลระบบที่ได้รับผลกระทบ',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ'],
                    ['key' => 'code', 'label' => 'รหัส'],
                    ['key' => 'description', 'label' => 'รายละเอียด'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => AffectedSystem::query()->orderBy('name'),
                'searchFields' => ['name', 'code', 'description'],
                'usageFilter' => 'tickets',
            ],
            'roles' => [
                'title' => 'Role',
                'subtitle' => 'จัดการสิทธิ์การใช้งานและ permission ของผู้ใช้',
                'icon' => 'shield',
                'emptyText' => 'ยังไม่มี role ในระบบ',
                'columns' => [
                    ['key' => 'name', 'label' => 'ชื่อ Role'],
                    ['key' => 'permissions', 'label' => 'Permissions'],
                    ['key' => 'updated_at', 'label' => 'อัปเดตล่าสุด'],
                ],
                'query' => fn () => Role::query()->with('permissions')->orderBy('name'),
                'searchFields' => ['name', 'permissions.name'],
                'usageFilter' => 'users',
            ],
            default => abort(404),
        };
    }

    private function newRecord(string $module): Model
    {
        return match ($module) {
            'users' => new User(),
            'departments' => new Department(),
            'positions' => new Position(),
            'locations' => new Location(),
            'categories' => new Category(),
            'subcategories' => new Subcategory(),
            'issue_types' => new IssueType(),
            'affected_systems' => new AffectedSystem(),
            'roles' => new Role(),
            default => abort(404),
        };
    }

    private function findRecord(string $module, int $id): Model
    {
        $modelClass = match ($module) {
            'users' => User::class,
            'departments' => Department::class,
            'positions' => Position::class,
            'locations' => Location::class,
            'categories' => Category::class,
            'subcategories' => Subcategory::class,
            'issue_types' => IssueType::class,
            'affected_systems' => AffectedSystem::class,
            'roles' => Role::class,
            default => abort(404),
        };

        return $modelClass::query()->findOrFail($id);
    }

    private function validateData(Request $request, string $module, ?Model $record = null): array
    {
        if ($module === 'users') {
            return $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($record?->id)],
                'password' => [$record ? 'nullable' : 'required', 'string', 'min:8'],
                'role' => ['required', Rule::exists('roles', 'name')],
                'department_name' => ['nullable', 'string', 'max:255', Rule::exists('departments', 'name')],
                'position_name' => ['nullable', 'string', 'max:255', Rule::exists('positions', 'name')],
            ]);
        }

        if ($module === 'roles') {
            return $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($record?->id)],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['string', Rule::exists('permissions', 'name')],
            ]);
        }

        $table = match ($module) {
            'departments' => 'departments',
            'positions' => 'positions',
            'locations' => 'locations',
            'categories' => 'categories',
            'subcategories' => 'subcategories',
            'issue_types' => 'issue_types',
            'affected_systems' => 'affected_systems',
            default => abort(404),
        };

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique($table, 'code')->ignore($record?->id)],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function fillRecord(Model $record, string $module, array $validated): void
    {
        if ($module === 'users') {
            $record->name = $validated['name'];
            $record->email = $validated['email'];
            $record->department_id = $this->resolveRelatedIdByName(Department::class, $validated['department_name'] ?? null, $record->department_id ?? null);
            $record->position_id = $this->resolveRelatedIdByName(Position::class, $validated['position_name'] ?? null, $record->position_id ?? null);

            if (! empty($validated['password'])) {
                $record->password = $validated['password'];
            }

            return;
        }

        if ($module === 'roles') {
            $record->name = $validated['name'];

            return;
        }

        $code = $this->resolveMasterCode($module, $validated['name'], $validated['code'] ?? null, $record);

        $record->uuid = $record->uuid ?: (string) Str::uuid();
        $record->name = $validated['name'];
        $record->code = $code;
        $record->description = $validated['description'] ?? null;
    }

    private function syncRelations(Model $record, string $module, array $validated): void
    {
        if ($module !== 'users') {
            if ($module === 'roles') {
                $record->syncPermissions($validated['permissions'] ?? []);
            }

            return;
        }

        $record->syncRoles([$validated['role']]);
    }

    private function rowData(string $module, Model $record): array
    {
        if ($module === 'users') {
            return [
                'id' => $record->id,
                'name' => $record->name,
                'email' => $record->email,
                'roles' => $record->roles->pluck('name')->all(),
                'department' => $record->department?->name ?: '-',
                'position' => $record->position?->name ?: '-',
                'updated_at' => optional($record->updated_at)->format('d/m/Y H:i') ?: '-',
            ];
        }

        if ($module === 'roles') {
            return [
                'id' => $record->id,
                'name' => $record->name,
                'permissions' => $record->permissions->pluck('name')->all(),
                'updated_at' => optional($record->updated_at)->format('d/m/Y H:i') ?: '-',
            ];
        }

        return [
            'id' => $record->id,
            'name' => $record->name,
            'code' => $record->code ?: '-',
            'description' => $record->description ?: '-',
            'updated_at' => optional($record->updated_at)->format('d/m/Y H:i') ?: '-',
        ];
    }

    private function departmentOptions()
    {
        return Department::query()->orderBy('name')->get(['id', 'name']);
    }

    private function positionOptions()
    {
        return Position::query()->orderBy('name')->get(['id', 'name']);
    }

    private function roleOptions()
    {
        return Role::query()->orderBy('name')->get(['name']);
    }

    private function permissionOptions()
    {
        return Permission::query()->orderBy('name')->get(['name']);
    }

    private function summaryCards(string $module): array
    {
        return match ($module) {
            'users' => [
                ['label' => 'ผู้ใช้ทั้งหมด', 'value' => User::count(), 'note' => 'บัญชีทั้งหมดในระบบ', 'tone' => 'sky'],
                ['label' => 'มีแผนก', 'value' => User::whereNotNull('department_id')->count(), 'note' => 'ผู้ใช้ที่ผูกกับแผนก', 'tone' => 'emerald'],
                ['label' => 'มีตำแหน่ง', 'value' => User::whereNotNull('position_id')->count(), 'note' => 'ผู้ใช้ที่ผูกกับตำแหน่ง', 'tone' => 'cyan'],
                ['label' => 'มี Role', 'value' => User::whereHas('roles')->count(), 'note' => 'ผู้ใช้ที่ได้รับสิทธิ์', 'tone' => 'rose'],
            ],
            'departments' => [
                ['label' => 'แผนกทั้งหมด', 'value' => Department::count(), 'note' => 'รายการแผนกใน master', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => Department::whereNotNull('code')->count(), 'note' => 'แผนกที่กำหนดรหัสไว้', 'tone' => 'amber'],
                ['label' => 'ถูกใช้งาน', 'value' => User::whereNotNull('department_id')->distinct('department_id')->count('department_id'), 'note' => 'แผนกที่มีผู้ใช้ผูกอยู่', 'tone' => 'emerald'],
            ],
            'positions' => [
                ['label' => 'ตำแหน่งทั้งหมด', 'value' => Position::count(), 'note' => 'รายการตำแหน่งใน master', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => Position::whereNotNull('code')->count(), 'note' => 'ตำแหน่งที่กำหนดรหัสไว้', 'tone' => 'amber'],
                ['label' => 'ถูกใช้งาน', 'value' => User::whereNotNull('position_id')->distinct('position_id')->count('position_id'), 'note' => 'ตำแหน่งที่มีผู้ใช้ผูกอยู่', 'tone' => 'emerald'],
            ],
            'locations' => [
                ['label' => 'สถานที่ทั้งหมด', 'value' => Location::count(), 'note' => 'รายการสถานที่ใน master', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => Location::whereNotNull('code')->count(), 'note' => 'สถานที่ที่กำหนดรหัสไว้', 'tone' => 'amber'],
                ['label' => 'ถูกใช้ใน Ticket', 'value' => Ticket::whereNotNull('location')->distinct('location')->count('location'), 'note' => 'สถานที่ที่มีการใช้งาน', 'tone' => 'cyan'],
            ],
            'categories' => [
                ['label' => 'หมวดหมู่งานทั้งหมด', 'value' => Category::count(), 'note' => 'รายการหมวดหมู่หลัก', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => Category::whereNotNull('code')->count(), 'note' => 'หมวดหมู่ที่ตั้งรหัสแล้ว', 'tone' => 'amber'],
                ['label' => 'ถูกใช้ใน Ticket', 'value' => Ticket::whereNotNull('category')->distinct('category')->count('category'), 'note' => 'หมวดหมู่ที่มีการใช้งาน', 'tone' => 'rose'],
            ],
            'subcategories' => [
                ['label' => 'หมวดงานย่อยทั้งหมด', 'value' => Subcategory::count(), 'note' => 'รายการหมวดงานย่อย', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => Subcategory::whereNotNull('code')->count(), 'note' => 'หมวดงานย่อยที่ตั้งรหัสแล้ว', 'tone' => 'amber'],
                ['label' => 'ถูกใช้ใน Ticket', 'value' => Ticket::whereNotNull('subcategory')->distinct('subcategory')->count('subcategory'), 'note' => 'หมวดงานย่อยที่มีการใช้งาน', 'tone' => 'orange'],
            ],
            'issue_types' => [
                ['label' => 'ประเภทปัญหาทั้งหมด', 'value' => IssueType::count(), 'note' => 'รายการประเภทปัญหา', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => IssueType::whereNotNull('code')->count(), 'note' => 'ประเภทปัญหาที่ตั้งรหัสแล้ว', 'tone' => 'amber'],
                ['label' => 'ถูกใช้ใน Ticket', 'value' => Ticket::whereNotNull('issue_type')->distinct('issue_type')->count('issue_type'), 'note' => 'ประเภทปัญหาที่มีการใช้งาน', 'tone' => 'emerald'],
            ],
            'affected_systems' => [
                ['label' => 'ระบบทั้งหมด', 'value' => AffectedSystem::count(), 'note' => 'รายการระบบที่ได้รับผลกระทบ', 'tone' => 'sky'],
                ['label' => 'มีรหัส', 'value' => AffectedSystem::whereNotNull('code')->count(), 'note' => 'ระบบที่ตั้งรหัสแล้ว', 'tone' => 'amber'],
                ['label' => 'ถูกใช้ใน Ticket', 'value' => Ticket::whereNotNull('affected_system')->distinct('affected_system')->count('affected_system'), 'note' => 'ระบบที่มีการใช้งาน', 'tone' => 'cyan'],
            ],
            'roles' => [
                ['label' => 'Role ทั้งหมด', 'value' => Role::count(), 'note' => 'ชุดสิทธิ์ที่มีในระบบ', 'tone' => 'indigo'],
                ['label' => 'Permission ทั้งหมด', 'value' => Permission::count(), 'note' => 'สิทธิ์ที่พร้อมใช้งาน', 'tone' => 'sky'],
                ['label' => 'ผู้ใช้ที่มี Role', 'value' => User::whereHas('roles')->count(), 'note' => 'บัญชีที่ได้รับสิทธิ์', 'tone' => 'emerald'],
            ],
            default => [
                ['label' => 'รายการทั้งหมด', 'value' => 0, 'note' => 'ยังไม่มีข้อมูลสรุป', 'tone' => 'slate'],
            ],
        };
    }

    private function resolveMasterCode(string $module, string $name, ?string $code, ?Model $record = null): ?string
    {
        $manualCode = trim((string) $code);

        if ($manualCode !== '') {
            return Str::upper(preg_replace('/[^A-Za-z0-9]+/', '-', $manualCode) ?: $manualCode);
        }

        $prefix = $this->masterCodePrefix($module);
        $base = Str::upper(Str::slug(Str::ascii($name), '-'));
        $base = preg_replace('/[^A-Z0-9-]+/', '-', $base) ?: '';
        $base = trim($base, '-');
        $base = $base !== '' ? $base : 'ITEM';
        $base = substr($base, 0, 32);

        $candidate = $prefix.'-'.$base;
        $suffix = 2;
        $table = $this->masterCodeTable($module);

        while ($this->masterCodeExists($table, $candidate, $record?->id)) {
            $candidate = $prefix.'-'.$base.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function resolveRelatedIdByName(string $modelClass, ?string $name, ?int $fallbackId = null): ?int
    {
        $resolvedName = trim((string) $name);

        if ($resolvedName === '') {
            return null;
        }

        $record = $modelClass::query()->where('name', $resolvedName)->first();

        return $record?->id ?? $fallbackId;
    }

    private function resolveUserFilterId(string $value, string $modelClass): ?int
    {
        if ($value === '' || $value === 'all') {
            return null;
        }

        if (ctype_digit($value)) {
            return (int) $value;
        }

        return $modelClass::query()->where('name', $value)->value('id');
    }

    private function resolveUserFilterLabel(string $value, string $modelClass): string
    {
        if ($value === '' || $value === 'all') {
            return '';
        }

        if (ctype_digit($value)) {
            return (string) ($modelClass::query()->whereKey((int) $value)->value('name') ?? '');
        }

        return $value;
    }

    private function applyIndexFilters(string $module, $query, string $search, string $status, string $usage, string $departmentFilter, string $positionFilter, array $config)
    {
        $searchFields = $config['searchFields'] ?? ['name', 'code', 'description'];

        if ($status === 'deleted') {
            $query->onlyTrashed();
        } elseif ($status === 'all') {
            $query->withTrashed();
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    if (str_contains($field, '.')) {
                        [$relation, $column] = explode('.', $field, 2);

                        $builder->orWhereHas($relation, fn ($relationQuery) => $relationQuery->where($column, 'like', '%'.$search.'%'));
                    } else {
                        $builder->orWhere($field, 'like', '%'.$search.'%');
                    }
                }
            });
        }

        if ($module === 'users') {
            if ($departmentFilter !== '' && $departmentFilter !== 'all') {
                if (ctype_digit($departmentFilter)) {
                    $query->where('department_id', (int) $departmentFilter);
                } else {
                    $query->whereHas('department', fn ($relationQuery) => $relationQuery->where('name', $departmentFilter));
                }
            }

            if ($positionFilter !== '' && $positionFilter !== 'all') {
                if (ctype_digit($positionFilter)) {
                    $query->where('position_id', (int) $positionFilter);
                } else {
                    $query->whereHas('position', fn ($relationQuery) => $relationQuery->where('name', $positionFilter));
                }
            }

            $query->when($usage === 'used', fn ($builder) => $builder->whereHas('roles'));
            $query->when($usage === 'unused', fn ($builder) => $builder->whereDoesntHave('roles'));

            return $query;
        }

        $usageFilter = $config['usageFilter'] ?? null;
        if ($usageFilter === 'users') {
            if ($module === 'users') {
                if ($departmentFilter !== '' && $departmentFilter !== 'all') {
                    if (ctype_digit($departmentFilter)) {
                        $query->where('department_id', (int) $departmentFilter);
                    } else {
                        $query->whereHas('department', fn ($relationQuery) => $relationQuery->where('name', $departmentFilter));
                    }
                }

                if ($positionFilter !== '' && $positionFilter !== 'all') {
                    if (ctype_digit($positionFilter)) {
                        $query->where('position_id', (int) $positionFilter);
                    } else {
                        $query->whereHas('position', fn ($relationQuery) => $relationQuery->where('name', $positionFilter));
                    }
                }
            }

            if ($module === 'departments') {
                $query->when($usage === 'used', fn ($builder) => $builder->whereHas('users'));
                $query->when($usage === 'unused', fn ($builder) => $builder->whereDoesntHave('users'));
            } elseif ($module === 'positions') {
                $query->when($usage === 'used', fn ($builder) => $builder->whereHas('users'));
                $query->when($usage === 'unused', fn ($builder) => $builder->whereDoesntHave('users'));
            } elseif ($module === 'roles') {
                $query->when($usage === 'used', fn ($builder) => $builder->whereHas('users'));
                $query->when($usage === 'unused', fn ($builder) => $builder->whereDoesntHave('users'));
            }
        } elseif ($usageFilter === 'tickets') {
            $ticketColumn = match ($module) {
                'locations' => 'location',
                'categories' => 'category',
                'subcategories' => 'subcategory',
                'issue_types' => 'issue_type',
                'affected_systems' => 'affected_system',
                default => null,
            };

            if ($ticketColumn !== null) {
                $query->when($usage === 'used', function ($builder) use ($ticketColumn) {
                    $builder->whereIn('name', Ticket::query()->select($ticketColumn)->whereNotNull($ticketColumn)->distinct());
                });

                $query->when($usage === 'unused', function ($builder) use ($ticketColumn) {
                    $builder->whereNotIn('name', Ticket::query()->select($ticketColumn)->whereNotNull($ticketColumn)->distinct());
                });
            }
        }

        return $query;
    }

    private function filterOptions(string $module): array
    {
        $options = [
            'status' => [
                'active' => 'ใช้งานอยู่',
                'all' => 'ทั้งหมด',
                'deleted' => 'ที่ลบแล้ว',
            ],
            'usage' => [
                'all' => 'ทั้งหมด',
            ],
        ];

        if (in_array($module, ['departments', 'positions', 'roles'], true)) {
            $options['usage'] = [
                'all' => 'ทั้งหมด',
                'used' => 'ถูกใช้งาน',
                'unused' => 'ยังไม่ถูกใช้งาน',
            ];
        }

        if (in_array($module, ['locations', 'categories', 'subcategories', 'issue_types', 'affected_systems'], true)) {
            $options['usage'] = [
                'all' => 'ทั้งหมด',
                'used' => 'ถูกใช้ใน Ticket',
                'unused' => 'ยังไม่ถูกใช้',
            ];
        }

        if ($module === 'users') {
            $options['usage'] = [
                'all' => 'ทั้งหมด',
                'used' => 'มี Role',
                'unused' => 'ไม่มี Role',
            ];
        }

        return $options;
    }

    private function masterCodePrefix(string $module): string
    {
        return match ($module) {
            'departments' => 'DEP',
            'positions' => 'POS',
            'locations' => 'LOC',
            'categories' => 'CAT',
            'subcategories' => 'SUB',
            'issue_types' => 'ISS',
            'affected_systems' => 'SYS',
            default => 'MST',
        };
    }

    private function masterCodeTable(string $module): string
    {
        return match ($module) {
            'departments' => 'departments',
            'positions' => 'positions',
            'locations' => 'locations',
            'categories' => 'categories',
            'subcategories' => 'subcategories',
            'issue_types' => 'issue_types',
            'affected_systems' => 'affected_systems',
            default => abort(404),
        };
    }

    private function masterCodeExists(string $table, string $code, ?int $ignoreId = null): bool
    {
        $query = $this->masterCodeQuery($table)->where('code', $code);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    private function masterCodeQuery(string $table)
    {
        return match ($table) {
            'departments' => Department::withTrashed(),
            'positions' => Position::withTrashed(),
            'locations' => Location::withTrashed(),
            'categories' => Category::withTrashed(),
            'subcategories' => Subcategory::withTrashed(),
            'issue_types' => IssueType::withTrashed(),
            'affected_systems' => AffectedSystem::withTrashed(),
            default => abort(404),
        };
    }
}