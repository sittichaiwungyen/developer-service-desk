<div>
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
    @endphp

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
                <div class="text-sm font-semibold text-slate-900">Ticket Queue</div>
                <div class="text-xs text-slate-500">รายการล่าสุดของงานที่เปิดอยู่</div>
            </div>
            <div class="text-xs text-slate-500">
                {{ isset($tickets) && method_exists($tickets, 'total') ? number_format($tickets->total()) : 0 }} รายการ
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.16em] text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Ticket</th>
                        <th class="px-5 py-3 font-semibold">เรื่อง</th>
                        <th class="px-5 py-3 font-semibold">Priority</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">ผู้รับผิดชอบ</th>
                        <th class="px-5 py-3 font-semibold">วันที่</th>
                        <th class="px-5 py-3 font-semibold text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $ticket->ticket_number }}</td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $ticket->subject }}</div>
                                <div class="mt-1 truncate text-xs text-slate-500">{{ $ticket->description }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="badge-soft {{ $priorityStyles[$ticket->priority] ?? 'bg-sky-50 text-sky-700 ring-emerald-200' }}">{{ $priorityLabels[$ticket->priority] ?? ucfirst($ticket->priority) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="badge-status {{ $statusStyles[$ticket->status] ?? 'bg-sky-50 text-sky-700 ring-sky-200' }}">{{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ optional($ticket->assignedTo)->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $ticket->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 shadow-sm transition hover:bg-sky-100 hover:border-sky-300">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 10s2.8-5 7.5-5 7.5 5 7.5 5-2.8 5-7.5 5-7.5-5-7.5-5Z" />
                                            <circle cx="10" cy="10" r="2.2" stroke-width="1.8" />
                                        </svg>
                                        <span>ดูรายละเอียด</span>
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 shadow-sm transition hover:bg-amber-100 hover:border-amber-300">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12.5 3.5l4 4L7 17H3v-4l9.5-9.5Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5l4 4" />
                                        </svg>
                                        <span>แก้ไข</span>
                                    </a>
                                    <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" data-swal-confirm data-swal-confirm-title="ยืนยันการลบ Ticket" data-swal-confirm-text="คุณต้องการลบ Ticket {{ $ticket->ticket_number }} ใช่หรือไม่" data-swal-confirm-button="ลบ Ticket" data-swal-cancel-button="ยกเลิก">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 shadow-sm transition hover:bg-rose-100 hover:border-rose-300">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h12" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 6V4.8A1.8 1.8 0 0 1 8.8 3h2.4A1.8 1.8 0 0 1 13 4.8V6m-7 0 .7 9.2A1.8 1.8 0 0 0 8.5 17h3a1.8 1.8 0 0 0 1.8-1.8L14 6" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 9v4m2-4v4" />
                                            </svg>
                                            <span>ลบ</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-sm text-slate-500">
                                ยังไม่มี Ticket ในระบบ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
