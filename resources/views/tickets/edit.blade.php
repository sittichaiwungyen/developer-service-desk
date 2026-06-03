<x-app-layout>
    @php
        $statusStyles = [
            'new' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'assigned' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'waiting_user' => 'bg-orange-50 text-orange-700 ring-orange-200',
            'testing' => 'bg-violet-50 text-violet-700 ring-violet-200',
            'done' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'closed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
        ];

        $statusLabels = [
            'new' => 'ใหม่',
            'assigned' => 'มอบหมายแล้ว',
            'in_progress' => 'กำลังดำเนินการ',
            'waiting_user' => 'รอผู้ใช้งาน',
            'testing' => 'ทดสอบ',
            'done' => 'เสร็จสิ้น',
            'closed' => 'ปิดงานแล้ว',
            'cancelled' => 'ยกเลิก',
        ];

        $roleGroups = $users->groupBy(function ($user) {
            return $user->roles->first()?->name ?? 'ไม่มี Role';
        });

        $roleBadges = [
            'Admin' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'Manager' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            'Developer' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Support' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Viewer' => 'bg-slate-50 text-slate-600 ring-slate-200',
            'ไม่มี Role' => 'bg-slate-50 text-slate-500 ring-slate-200',
        ];

        $roleLabels = [
            'Admin' => 'ผู้ดูแลระบบ',
            'Manager' => 'ผู้จัดการ',
            'Developer' => 'นักพัฒนา',
            'Support' => 'Support',
            'Viewer' => 'ผู้ดูแลทั่วไป',
            'ไม่มี Role' => 'ไม่มี Role',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    แก้ไข Ticket
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    ปรับปรุงรายละเอียด Ticket เดิมให้ถูกต้องและอัปเดตล่าสุด
                </p>
            </div>
            <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex min-w-[220px] items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-base font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12.5 4.5L7 10l5.5 5.5" />
                </svg>
                <span>กลับไปหน้ารายละเอียด</span>
            </a>
        </div>
    </x-slot>

    <div class="w-full">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-semibold text-slate-900">แบบฟอร์มแก้ไข Ticket</h3>
                <p class="mt-1 text-sm text-slate-500">แก้ไขข้อมูลที่จำเป็นแล้วกดบันทึกเพื่ออัปเดตข้อมูลของ Ticket นี้</p>
            </div>
            <div class="p-5 sm:p-6">
                <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ชื่อผู้แจ้ง</label>
                            <input name="requester_name" type="text" value="{{ old('requester_name', $ticket->requester_name) }}" class="input-modern" placeholder="กรอกชื่อผู้แจ้ง" />
                            @error('requester_name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">อีเมลผู้แจ้ง</label>
                            <input name="requester_email" type="email" value="{{ old('requester_email', $ticket->requester_email) }}" class="input-modern" placeholder="name@hospital.go.th" />
                            @error('requester_email') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">แผนก</label>
                            <input name="department_name" list="departments-list" data-target="#department_id" type="text" value="{{ old('department_name', $ticket->department?->name ?? '') }}" class="input-modern" placeholder="เลือกหรือพิมพ์ชื่อแผนก" />
                            <input type="hidden" name="department_id" id="department_id" value="{{ old('department_id', $ticket->department_id) }}" />
                            <datalist id="departments-list">
                                @foreach ($departments as $department)
                                    <option value="{{ $department->name }}" data-id="{{ $department->id }}"></option>
                                @endforeach
                            </datalist>
                            @error('department_id') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ตำแหน่งผู้แจ้ง</label>
                            <input name="requester_position" type="text" value="{{ old('requester_position', $ticket->requester_position) }}" class="input-modern" placeholder="กรอกตำแหน่งผู้แจ้ง" />
                            @error('requester_position') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">เบอร์ติดต่อ</label>
                            <input name="contact_phone" type="text" value="{{ old('contact_phone', $ticket->contact_phone) }}" class="input-modern" placeholder="กรอกเบอร์ติดต่อ" />
                            @error('contact_phone') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">สถานที่</label>
                            <input name="location" type="text" value="{{ old('location', $ticket->location) }}" class="input-modern" placeholder="เช่น อาคาร / ชั้น / ห้อง" />
                            @error('location') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">หัวข้อเรื่อง</label>
                        <input name="subject" type="text" value="{{ old('subject', $ticket->subject) }}" class="input-modern" placeholder="เช่น เข้าใช้งาน HIS ไม่ได้ที่หอผู้ป่วย" />
                        @error('subject') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">รายละเอียด</label>
                        <input id="ticket_description" name="description" type="hidden" value="{{ old('description', $ticket->description) }}" />
                        <trix-editor input="ticket_description" class="trix-editor rounded-xl border border-slate-200 bg-white shadow-sm"></trix-editor>
                        @error('description') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">หมวดหมู่</label>
                            <input name="category" type="text" value="{{ old('category', $ticket->category) }}" class="input-modern" placeholder="เช่น Hardware / Software / Network" />
                            @error('category') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">หมวดย่อย</label>
                            <input name="subcategory" type="text" value="{{ old('subcategory', $ticket->subcategory) }}" class="input-modern" placeholder="เช่น HIS / Printer / WiFi" />
                            @error('subcategory') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ประเภทปัญหา</label>
                            <input name="issue_type" type="text" value="{{ old('issue_type', $ticket->issue_type) }}" class="input-modern" placeholder="เช่น Login / Error / Request" />
                            @error('issue_type') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ระบบที่ได้รับผลกระทบ</label>
                            <input name="affected_system" type="text" value="{{ old('affected_system', $ticket->affected_system) }}" class="input-modern" placeholder="เช่น HIS, LIS, PACS, ERP" />
                            @error('affected_system') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Asset Tag</label>
                            <input name="asset_tag" type="text" value="{{ old('asset_tag', $ticket->asset_tag) }}" class="input-modern" placeholder="รหัสครุภัณฑ์ / Serial" />
                            @error('asset_tag') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">IP Address</label>
                            <input name="ip_address" type="text" value="{{ old('ip_address', $ticket->ip_address) }}" class="input-modern" placeholder="192.168.x.x" />
                            @error('ip_address') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ความสำคัญ</label>
                            <select name="priority" class="select-modern">
                                <option value="low" @selected(old('priority', $ticket->priority) === 'low')>ต่ำ</option>
                                <option value="medium" @selected(old('priority', $ticket->priority) === 'medium')>ปานกลาง</option>
                                <option value="high" @selected(old('priority', $ticket->priority) === 'high')>สูง</option>
                                <option value="critical" @selected(old('priority', $ticket->priority) === 'critical')>Critical</option>
                            </select>
                            @error('priority') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">ระดับผลกระทบ</label>
                            <select name="impact_level" class="select-modern">
                                <option value="low" @selected(old('impact_level', $ticket->impact_level) === 'low')>ต่ำ</option>
                                <option value="medium" @selected(old('impact_level', $ticket->impact_level) === 'medium')>ปานกลาง</option>
                                <option value="high" @selected(old('impact_level', $ticket->impact_level) === 'high')>สูง</option>
                                <option value="critical" @selected(old('impact_level', $ticket->impact_level) === 'critical')>Critical</option>
                            </select>
                            @error('impact_level') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">วิธีแก้ไข</label>
                        <textarea name="resolution" class="textarea-modern" rows="3" placeholder="บันทึกวิธีแก้ไขหรือแนวทางเบื้องต้น">{{ old('resolution', $ticket->resolution) }}</textarea>
                        @error('resolution') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">สาเหตุหลัก</label>
                        <textarea name="root_cause" class="textarea-modern" rows="3" placeholder="สรุปสาเหตุหลักของปัญหา">{{ old('root_cause', $ticket->root_cause) }}</textarea>
                        @error('root_cause') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input name="follow_up_required" id="follow_up_required" type="checkbox" value="1" @checked(old('follow_up_required', $ticket->follow_up_required)) class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
                        <label for="follow_up_required" class="text-sm text-slate-700">ต้องติดตามงานต่อ</label>
                    </div>

                    <div class="mt-2 flex items-center justify-between gap-3">
                        <div class="text-xs text-slate-500">Ticket เลขที่ {{ $ticket->ticket_number }}</div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">ยกเลิก</a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

            <div class="mt-6 grid gap-6 xl:grid-cols-2">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">จัดการสถานะ</h3>
                    </div>
                    <div class="flex flex-wrap gap-3 p-5">
                        @foreach ([
                            ['action' => 'assign_to_me', 'label' => 'มอบหมายให้ฉัน', 'class' => 'border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:border-indigo-300'],
                            ['action' => 'start_work', 'label' => 'เริ่มทำงาน', 'class' => 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:border-amber-300'],
                            ['action' => 'waiting_user', 'label' => 'รอผู้ใช้งาน', 'class' => 'border-orange-200 bg-orange-50 text-orange-700 hover:bg-orange-100 hover:border-orange-300'],
                            ['action' => 'testing', 'label' => 'ทดสอบ', 'class' => 'border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100 hover:border-violet-300'],
                            ['action' => 'done', 'label' => 'เสร็จสิ้น', 'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300'],
                            ['action' => 'closed', 'label' => 'ปิดงาน', 'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300'],
                            ['action' => 'cancelled', 'label' => 'ยกเลิก', 'class' => 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:border-rose-300'],
                        ] as $statusAction)
                            <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                                @csrf
                                <input type="hidden" name="action" value="{{ $statusAction['action'] }}">
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl border px-4 py-2.5 text-sm font-semibold shadow-sm transition {{ $statusAction['class'] }}">
                                    {{ $statusAction['label'] }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">มอบหมายผู้รับผิดชอบ</h3>
                    </div>
                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="space-y-4 p-5">
                        @csrf
                        <div class="flex flex-wrap gap-2">
                            @foreach ($roleBadges as $roleName => $badgeClass)
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">
                                    {{ $roleLabels[$roleName] ?? $roleName }}
                                </span>
                            @endforeach
                        </div>
                        <div class="w-full max-w-[18rem]">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">เลือกผู้รับผิดชอบ</label>
                            <input name="assignee_name" list="users-list" data-target="#assign_user_id" type="text" value="{{ old('assignee_name', $ticket->assignedTo?->name ?? '') }}" class="input-modern" placeholder="พิมพ์หรือเลือกชื่อผู้รับผิดชอบ" style="width:100%; max-width:18rem;" />
                            <input type="hidden" name="user_id" id="assign_user_id" value="{{ old('user_id', $ticket->assigned_to) }}" />
                            <datalist id="users-list">
                                @foreach ($users as $u)
                                    <option value="{{ $u->name }}" data-id="{{ $u->id }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            บันทึกการมอบหมาย
                        </button>
                    </form>
                </div>
            </div>
    </div>
</x-app-layout>
