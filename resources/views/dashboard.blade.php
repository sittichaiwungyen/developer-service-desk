<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="section-kicker">ภาพรวมระบบ</div>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Dashboard</h2>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">
                    ภาพรวมการทำงานของระบบ Service Desk, Work Log, Project และ Master Data ในธีมเดียวกัน
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('tickets.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">ไปที่ Tickets</a>
                <a href="{{ route('master.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Master Data</a>
            </div>
        </div>
    </x-slot>

    @php
        $stats = [
            ['label' => 'Tickets ทั้งหมด', 'value' => \App\Models\Ticket::count(), 'subtitle' => 'รายการทั้งหมดในระบบ', 'tone' => 'metric-card-accent--cyan'],
            ['label' => 'Tickets เปิดอยู่', 'value' => \App\Models\Ticket::whereIn('status', ['new', 'assigned', 'in_progress', 'waiting_user', 'testing'])->count(), 'subtitle' => 'งานที่ยังต้องติดตาม', 'tone' => 'metric-card-accent--violet'],
            ['label' => 'งานเสร็จแล้ว', 'value' => \App\Models\Ticket::whereIn('status', ['done', 'closed'])->count(), 'subtitle' => 'งานที่ปิดเรียบร้อย', 'tone' => 'metric-card-accent--amber'],
            ['label' => 'Critical', 'value' => \App\Models\Ticket::where('priority', 'critical')->count(), 'subtitle' => 'งานเร่งด่วน', 'tone' => 'metric-card-accent--rose'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="metric-card-accent {{ $stat['tone'] }}">
                    <div class="metric-card-title">{{ $stat['label'] }}</div>
                    <div class="metric-card-number">{{ number_format($stat['value']) }}</div>
                    <div class="metric-card-subtitle">{{ $stat['subtitle'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.6fr,1fr]">
            <div class="glass-panel p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 pb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">ทางลัดงานหลัก</h3>
                        <p class="mt-1 text-sm text-slate-500">เข้าถึงหน้าที่ใช้งานบ่อยได้ทันที</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('tickets.create') }}" wire:navigate class="rounded-2xl border border-slate-200 bg-gradient-to-br from-sky-50 via-white to-sky-100 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="section-kicker">Tickets</div>
                        <div class="mt-4 text-lg font-bold text-sky-800">สร้าง Ticket ใหม่</div>
                        <p class="mt-2 text-sm text-slate-600">บันทึกปัญหาใหม่เข้า queue ของทีม</p>
                    </a>

                    <a href="{{ route('tickets.index') }}" wire:navigate class="rounded-2xl border border-slate-200 bg-gradient-to-br from-indigo-50 via-white to-indigo-100 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="section-kicker">Queue</div>
                        <div class="mt-4 text-lg font-bold text-indigo-800">เปิด Ticket Queue</div>
                        <p class="mt-2 text-sm text-slate-600">ดูงานค้างและติดตามสถานะทั้งหมด</p>
                    </a>

                    <a href="{{ route('master.index') }}" wire:navigate class="rounded-2xl border border-slate-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="section-kicker">Master</div>
                        <div class="mt-4 text-lg font-bold text-emerald-800">จัดการข้อมูลหลัก</div>
                        <p class="mt-2 text-sm text-slate-600">จัดการผู้ใช้ แผนก ตำแหน่ง และหมวดหมู่</p>
                    </a>

                    <a href="{{ route('profile') }}" wire:navigate class="rounded-2xl border border-slate-200 bg-gradient-to-br from-rose-50 via-white to-rose-100 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="section-kicker">Profile</div>
                        <div class="mt-4 text-lg font-bold text-rose-800">โปรไฟล์ของฉัน</div>
                        <p class="mt-2 text-sm text-slate-600">ปรับข้อมูลส่วนตัวและรหัสผ่าน</p>
                    </a>
                </div>
            </div>

            <div class="glass-panel p-5 sm:p-6">
                <h3 class="text-lg font-semibold text-slate-900">สถานะระบบ</h3>
                <p class="mt-1 text-sm text-slate-500">ธีมปัจจุบันถูกออกแบบให้ใช้ร่วมกันทุกหน้าหลัก</p>

                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">UI Theme</div>
                        <div class="mt-1 text-sm text-slate-500">Glass panel + accent cards</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">Typography</div>
                        <div class="mt-1 text-sm text-slate-500">ขนาดฟอนต์และระยะห่างที่อ่านง่ายขึ้น</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">Consistency</div>
                        <div class="mt-1 text-sm text-slate-500">ใช้ชุดสีและ card style เดียวกันกับ Master Data</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
