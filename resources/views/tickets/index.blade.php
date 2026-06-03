<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                Service Desk - Tickets
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Manage hospital support requests, issues, and the developer queue.
            </p>
            <a href="{{ route('tickets.create') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                สร้าง Ticket
            </a>
        </div>
    </x-slot>

    @php
        $summary = [
            'total' => \App\Models\Ticket::count(),
            'open' => \App\Models\Ticket::whereIn('status', ['new', 'assigned', 'in_progress', 'waiting_user', 'testing'])->count(),
            'done' => \App\Models\Ticket::whereIn('status', ['done', 'closed'])->count(),
            'critical' => \App\Models\Ticket::where('priority', 'critical')->count(),
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="metric-card">
                <div class="metric-label">Tickets ทั้งหมด</div>
                <div class="mt-2 metric-value">{{ number_format($summary['total']) }}</div>
                <div class="mt-2 text-sm text-slate-500">รายการคำขอทั้งหมดในระบบ</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Tickets ที่ยังเปิดอยู่</div>
                <div class="mt-2 metric-value">{{ number_format($summary['open']) }}</div>
                <div class="mt-2 text-sm text-slate-500">ยังต้องให้ทีมตรวจสอบ</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">งานเสร็จแล้ว</div>
                <div class="mt-2 metric-value">{{ number_format($summary['done']) }}</div>
                <div class="mt-2 text-sm text-slate-500">งานที่แก้ไขและปิดแล้ว</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Priority ระดับ Critical</div>
                <div class="mt-2 metric-value text-rose-700">{{ number_format($summary['critical']) }}</div>
                <div class="mt-2 text-sm text-slate-500">Tickets ด่วนที่ต้องเร่ง escalate</div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap gap-2 text-xs font-semibold text-slate-600">
                    <span class="rounded-full bg-sky-50 px-3 py-1.5 text-sky-700 ring-1 ring-sky-200">ใหม่</span>
                    <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-indigo-700 ring-1 ring-indigo-200">มอบหมายแล้ว</span>
                    <span class="rounded-full bg-amber-50 px-3 py-1.5 text-amber-700 ring-1 ring-amber-200">กำลังดำเนินการ</span>
                    <span class="rounded-full bg-orange-50 px-3 py-1.5 text-orange-700 ring-1 ring-orange-200">รอผู้ใช้งาน</span>
                    <span class="rounded-full bg-violet-50 px-3 py-1.5 text-violet-700 ring-1 ring-violet-200">ทดสอบ</span>
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 ring-1 ring-emerald-200">เสร็จสิ้น</span>
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 ring-1 ring-emerald-200">ปิดงานแล้ว</span>
                    <span class="rounded-full bg-rose-50 px-3 py-1.5 text-rose-700 ring-1 ring-rose-200">ยกเลิก</span>
                </div>
            </div>
            <div class="p-5">
                @livewire(\App\Livewire\Tickets\Index::class)
            </div>
        </div>
    </div>
</x-app-layout>
