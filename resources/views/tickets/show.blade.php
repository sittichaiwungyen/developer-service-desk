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

        $priorityStyles = [
            'low' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'medium' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'high' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'critical' => 'bg-rose-50 text-rose-700 ring-rose-200',
        ];

        $priorityLabels = [
            'low' => 'ต่ำ',
            'medium' => 'ปานกลาง',
            'high' => 'สูง',
            'critical' => 'Critical',
        ];

        $historyFilters = [
            'all' => 'ทั้งหมด',
            'status' => 'เฉพาะสถานะ',
            'assignment' => 'เฉพาะการมอบหมาย',
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

        $historyCounts = [
            'all' => $histories->count(),
            'status' => $histories->filter(fn ($history) => in_array($history->action, ['created', 'status_changed'], true))->count(),
            'assignment' => $histories->filter(fn ($history) => in_array($history->action, ['created', 'assigned'], true))->count(),
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    {{ $ticket->ticket_number }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $ticket->subject }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center justify-center rounded-xl border border-sky-200 bg-sky-50 px-4 py-2.5 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-100 hover:border-sky-300">
                    กลับไป Ticket Queue
                </a>
                <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-700 shadow-sm transition hover:bg-amber-100 hover:border-amber-300">
                    แก้ไข Ticket
                </a>
                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" data-swal-confirm data-swal-confirm-title="ยืนยันการลบ Ticket" data-swal-confirm-text="คุณต้องการลบ Ticket {{ $ticket->ticket_number }} ใช่หรือไม่" data-swal-confirm-button="ลบ Ticket" data-swal-cancel-button="ยกเลิก">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 shadow-sm transition hover:bg-rose-100 hover:border-rose-300">
                        ลบ Ticket
                    </button>
                </form>
                <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    สร้าง Ticket
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="metric-card">
                <div class="metric-label">ความสำคัญ</div>
                <div class="mt-2 metric-value">{{ ucfirst($ticket->priority) }}</div>
                <div class="mt-2 text-sm text-slate-500">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">แผนก</div>
                <div class="mt-2 metric-value text-2xl">{{ $ticket->department?->name ?? '-' }}</div>
                <div class="mt-2 text-sm text-slate-500">ผู้แจ้ง: {{ $ticket->requester_name ?? '-' }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">ระดับผลกระทบ</div>
                <div class="mt-2 metric-value">{{ ucfirst($ticket->impact_level) }}</div>
                <div class="mt-2 text-sm text-slate-500">{{ $ticket->location ?? 'ยังไม่ระบุสถานที่' }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">วันที่บันทึก</div>
                <div class="mt-2 metric-value text-2xl">{{ $ticket->created_at->format('d M') }}</div>
                <div class="mt-2 text-sm text-slate-500">{{ $ticket->created_at->format('H:i') }}</div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,0.9fr)]">
            <div class="space-y-6">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">รายละเอียดปัญหา</h3>
                    </div>
                    <div class="grid gap-4 p-5 sm:grid-cols-2">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">ผู้แจ้ง</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950">{{ $ticket->requester_name ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $ticket->requester_email ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $ticket->requester_position ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-600">ข้อมูลติดต่อ</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950">{{ $ticket->contact_phone ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $ticket->location ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $ticket->ip_address ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-600">การจัดหมวดหมู่</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950">{{ $ticket->category ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $ticket->subcategory ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $ticket->issue_type ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-600">ระบบ / Asset</div>
                            <div class="mt-1 text-sm font-semibold text-slate-950">{{ $ticket->affected_system ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">Asset Tag: {{ $ticket->asset_tag ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">แผนก: {{ $ticket->department?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">รายละเอียด</h3>
                    </div>
                    <div class="max-w-none p-5 leading-7 text-slate-700">
                        {!! $ticket->description ?: '<p class="text-slate-400">ยังไม่มีรายละเอียด</p>' !!}
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">วิธีแก้ไขและสาเหตุหลัก</h3>
                    </div>
                    <div class="grid gap-4 p-5 sm:grid-cols-2">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">วิธีแก้ไข</div>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $ticket->resolution ?: 'ยังไม่ได้บันทึกวิธีแก้ไข' }}</p>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-600">สาเหตุหลัก</div>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $ticket->root_cause ?: 'ยังไม่ได้บันทึกสาเหตุหลัก' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">สถานะ</h3>
                        <span class="badge-status {{ $statusStyles[$ticket->status] ?? 'bg-slate-50 text-slate-600 ring-slate-200' }}">{{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    </div>
                    <div class="space-y-4 p-5 text-sm text-slate-700">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">สถานะ</span>
                            <span class="badge-status {{ $statusStyles[$ticket->status] ?? 'bg-slate-50 text-slate-600 ring-slate-200' }}">{{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">ความสำคัญ</span>
                            <span class="badge-soft {{ $priorityStyles[$ticket->priority] ?? 'bg-slate-50 text-slate-600 ring-slate-200' }}">{{ $priorityLabels[$ticket->priority] ?? ucfirst($ticket->priority) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">ระดับผลกระทบ</span>
                            <span>{{ ucfirst($ticket->impact_level) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">ติดตามงานต่อ</span>
                            <span>{{ $ticket->follow_up_required ? 'ต้องติดตาม' : 'ไม่ต้องติดตาม' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">ผู้รับผิดชอบ</span>
                            <span>{{ $ticket->assignedTo?->name ?? 'ยังไม่มอบหมาย' }}</span>
                        </div>
                    </div>
                </div>

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
                    <div class="border-t border-slate-200 px-5 py-4">
                        <form method="POST" action="{{ route('tickets.status.update', $ticket) }}" class="flex flex-wrap items-end gap-3">
                            @csrf
                            <input type="hidden" name="action" value="reverse_status">
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                ย้อนสถานะ
                            </button>
                        </form>
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

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">ไฟล์แนบ</h3>
                    </div>
                    <div class="space-y-4 p-5">
                        @forelse ($ticket->attachments as $attachment)
                            @php
                                $url = asset('storage/' . $attachment->path);
                                $isImage = str_starts_with($attachment->mime_type ?? '', 'image/');
                            @endphp
                            <div class="rounded-xl border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $attachment->filename }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $attachment->mime_type ?? 'Unknown type' }} · {{ number_format(($attachment->size ?? 0) / 1024, 1) }} KB</div>
                                    </div>
                                    <a href="{{ $url }}" target="_blank" class="text-sm font-semibold text-sky-700 hover:text-sky-800">เปิดดู</a>
                                </div>
                                @if ($isImage)
                                    <img src="{{ $url }}" alt="{{ $attachment->filename }}" class="mt-4 max-h-72 w-full rounded-xl border border-slate-200 object-contain bg-slate-50" />
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">ยังไม่มีไฟล์แนบ</p>
                        @endforelse
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h3 class="text-base font-semibold text-slate-900">ประวัติการทำงาน</h3>
                    </div>
                    <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($historyFilters as $value => $label)
                                <a href="{{ route('tickets.show', ['ticket' => $ticket, 'history_filter' => $value]) }}" class="inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-sm font-semibold transition {{ $historyFilter === $value ? 'border-slate-900 bg-slate-900 text-white shadow-sm' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                                    <span>{{ $label }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ $historyFilter === $value ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $historyCounts[$value] ?? 0 }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        @forelse ($histories as $history)
                            @php
                                $changes = json_decode($history->changes, true) ?? [];
                                $statusChange = $changes['status'] ?? null;
                                $assignmentChange = $changes['assigned_to'] ?? null;
                            @endphp
                            <div class="rounded-xl border border-slate-200 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $history->action === 'created' ? 'สร้าง Ticket' : ($history->action === 'assigned' ? 'มอบหมายงาน' : 'เปลี่ยนสถานะ') }}</div>
                                        <div class="mt-1 text-sm text-slate-500">
                                            โดย {{ $history->user?->name ?? 'ระบบ' }} · {{ $history->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 space-y-1 text-sm text-slate-600">
                                    @if ($statusChange)
                                        <div>สถานะ: {{ $statusLabels[$statusChange['from'] ?? ''] ?? ($statusChange['from'] ?? '-') }} → {{ $statusLabels[$statusChange['to'] ?? ''] ?? ($statusChange['to'] ?? '-') }}</div>
                                    @endif
                                    @if ($assignmentChange)
                                        <div>
                                            ผู้รับผิดชอบ:
                                            @php
                                                $fromUser = isset($assignmentChange['from']) ? \App\Models\User::find($assignmentChange['from']) : null;
                                                $toUser = isset($assignmentChange['to']) ? \App\Models\User::find($assignmentChange['to']) : null;
                                            @endphp
                                            {{ $fromUser?->name ?? 'ยังไม่มอบหมาย' }} → {{ $toUser?->name ?? 'ยังไม่มอบหมาย' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">ยังไม่มีประวัติการทำงาน</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
