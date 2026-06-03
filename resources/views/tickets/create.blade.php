<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    สร้าง Ticket
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    บันทึกปัญหาใหม่พร้อมรายละเอียด, ไฟล์แนบ และ rich text description
                </p>
            </div>
            <a href="{{ route('tickets.index') }}" class="inline-flex min-w-[220px] items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-base font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12.5 4.5L7 10l5.5 5.5" />
                </svg>
                <span>กลับไป Ticket Queue</span>
            </a>
        </div>
    </x-slot>

    <div class="w-full">
        <div class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-semibold text-slate-900">แบบฟอร์ม Ticket ใหม่</h3>
                <p class="mt-1 text-sm text-slate-500">กรอกข้อมูลให้ครบเพื่อให้ทีมตรวจสอบและติดตามงานได้ง่าย</p>
            </div>
            <div class="p-5 sm:p-6">
                @livewire(\App\Livewire\Tickets\CreateTicket::class)
            </div>
        </div>
    </div>
</x-app-layout>
