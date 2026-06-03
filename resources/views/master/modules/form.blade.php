<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            </div>
            <a href="{{ route("master.{$module}") }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12.5 4.5L7 10l5.5 5.5" /></svg>
                <span>กลับไปหน้ารายการ</span>
            </a>
        </div>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ $record ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูลใหม่' }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $record ? 'ปรับปรุงข้อมูลเดิมแล้วบันทึก' : 'กรอกข้อมูลให้ครบเพื่อสร้างรายการใหม่' }}</p>
        </div>

        <div class="p-5 sm:p-6">
            <form method="POST" action="{{ $actionUrl }}" class="space-y-6">
                @csrf
                @if ($method !== 'POST')
                    @method($method)
                @endif

                @if ($module === 'users')
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ชื่อ-สกุล</label>
                            <input name="name" type="text" value="{{ old('name', $record->name ?? '') }}" class="input-modern" placeholder="กรอกชื่อผู้ใช้" />
                            @error('name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">อีเมล</label>
                            <input name="email" type="email" value="{{ old('email', $record->email ?? '') }}" class="input-modern" placeholder="name@hospital.go.th" />
                            @error('email') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Role</label>
                            <select name="role" class="select-modern">
                                <option value="">เลือก Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected(old('role', $record?->roles?->first()?->name) === $role->name)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">แผนก</label>
                            <input name="department_name" list="master-user-departments" type="text" value="{{ old('department_name', $record?->department?->name ?? '') }}" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาแผนกจาก master" />
                            <datalist id="master-user-departments">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->name }}"></option>
                                @endforeach
                            </datalist>
                            @error('department_name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ตำแหน่ง</label>
                            <input name="position_name" list="master-user-positions" type="text" value="{{ old('position_name', $record?->position?->name ?? '') }}" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาตำแหน่งจาก master" />
                            <datalist id="master-user-positions">
                                @foreach ($positions as $position)
                                    <option value="{{ $position->name }}"></option>
                                @endforeach
                            </datalist>
                            @error('position_name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">รหัสผ่าน{{ $record ? ' (เว้นว่างถ้าไม่ต้องการเปลี่ยน)' : '' }}</label>
                            <input name="password" type="password" class="input-modern" placeholder="กรอกรหัสผ่าน" />
                            @error('password') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @elseif ($module === 'roles')
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ชื่อ Role</label>
                            <input name="name" type="text" value="{{ old('name', $record->name ?? '') }}" class="input-modern" placeholder="กรอกชื่อ role" />
                            @error('name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Permissions</label>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($permissions as $permission)
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-100">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked(in_array($permission->name, old('permissions', $record?->permissions?->pluck('name')->all() ?? []))) class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('permissions') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ชื่อ</label>
                            <input name="name" type="text" value="{{ old('name', $record->name ?? '') }}" class="input-modern" placeholder="กรอกชื่อรายการ" />
                            @error('name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">รหัส</label>
                            <input name="code" type="text" value="{{ old('code', $record->code ?? '') }}" class="input-modern" placeholder="เว้นว่างให้ระบบสร้างอัตโนมัติ" />
                            @error('code') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">รายละเอียด</label>
                        <textarea name="description" rows="4" class="textarea-modern" placeholder="รายละเอียดเพิ่มเติม">{{ old('description', $record->description ?? '') }}</textarea>
                        @error('description') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <a href="{{ route("master.{$module}") }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">ยกเลิก</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        {{ $record ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>