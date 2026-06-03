<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Master Data</h2>
            <p class="mt-1 text-sm text-slate-500">จัดการข้อมูลหลักของระบบ เช่น ผู้ใช้ แผนก ตำแหน่งงาน สถานที่ และหมวดหมู่</p>
        </div>
    </x-slot>

    @php
        $cardStyles = [
            'master.users' => [
                'card' => 'bg-gradient-to-br from-rose-200 via-white to-pink-200',
                'iconBg' => 'bg-rose-400',
                'iconText' => 'text-white',
                'titleText' => 'text-rose-800',
                'accent' => 'bg-rose-400',
            ],
            'master.departments' => [
                'card' => 'bg-gradient-to-br from-indigo-200 via-white to-sky-200',
                'iconBg' => 'bg-indigo-400',
                'iconText' => 'text-white',
                'titleText' => 'text-indigo-800',
                'accent' => 'bg-indigo-400',
            ],
            'master.positions' => [
                'card' => 'bg-gradient-to-br from-sky-200 via-white to-cyan-200',
                'iconBg' => 'bg-sky-400',
                'iconText' => 'text-white',
                'titleText' => 'text-sky-800',
                'accent' => 'bg-sky-400',
            ],
            'master.locations' => [
                'card' => 'bg-gradient-to-br from-emerald-200 via-white to-teal-200',
                'iconBg' => 'bg-emerald-400',
                'iconText' => 'text-white',
                'titleText' => 'text-emerald-800',
                'accent' => 'bg-emerald-400',
            ],
            'master.categories' => [
                'card' => 'bg-gradient-to-br from-amber-200 via-white to-yellow-200',
                'iconBg' => 'bg-amber-400',
                'iconText' => 'text-white',
                'titleText' => 'text-amber-800',
                'accent' => 'bg-amber-400',
            ],
            'master.subcategories' => [
                'card' => 'bg-gradient-to-br from-orange-200 via-white to-amber-200',
                'iconBg' => 'bg-orange-400',
                'iconText' => 'text-white',
                'titleText' => 'text-orange-800',
                'accent' => 'bg-orange-400',
            ],
            'master.issue_types' => [
                'card' => 'bg-gradient-to-br from-fuchsia-200 via-white to-rose-200',
                'iconBg' => 'bg-fuchsia-400',
                'iconText' => 'text-white',
                'titleText' => 'text-fuchsia-800',
                'accent' => 'bg-fuchsia-400',
            ],
            'master.affected_systems' => [
                'card' => 'bg-gradient-to-br from-cyan-200 via-white to-sky-200',
                'iconBg' => 'bg-cyan-400',
                'iconText' => 'text-white',
                'titleText' => 'text-cyan-800',
                'accent' => 'bg-cyan-400',
            ],
            'master.roles' => [
                'card' => 'bg-gradient-to-br from-sky-100 via-white to-indigo-100',
                'iconBg' => 'bg-indigo-400',
                'iconText' => 'text-white',
                'titleText' => 'text-indigo-900',
                'accent' => 'bg-indigo-400',
            ],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($modules as $module)
                @php($style = $cardStyles[$module['route']] ?? ['card' => 'bg-gradient-to-br from-slate-100 via-white to-slate-200', 'iconBg' => 'bg-slate-400', 'iconText' => 'text-white', 'titleText' => 'text-slate-800', 'accent' => 'bg-slate-400'])
                <a href="{{ route($module['route']) }}" wire:navigate data-no-loading="1" class="group relative overflow-hidden rounded-[2rem] border border-white/80 {{ $style['card'] }} p-5 shadow-[0_14px_32px_-22px_rgba(15,23,42,0.34)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_-24px_rgba(15,23,42,0.42)]">
                    <div class="pointer-events-none absolute inset-0 opacity-45 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.92),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.58),transparent_30%)]"></div>
                    <div class="pointer-events-none absolute left-0 top-0 h-1.5 w-full {{ $style['accent'] }}"></div>
                    <div class="flex items-start justify-between gap-5">
                        <div class="relative flex h-16 w-16 shrink-0 items-center justify-center rounded-[1.25rem] border border-white/75 {{ $style['iconBg'] }} {{ $style['iconText'] }} shadow-md ring-1 ring-white/30">
                            @switch($module['icon'])
                                @case('users')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 19v-1a3.5 3.5 0 0 1 3.5-3.5h2" /><circle cx="9.5" cy="8" r="3" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 19v-1a3.5 3.5 0 0 1 3.5-3.5h2" /><circle cx="16.5" cy="9" r="2.4" stroke-width="1.8" /></svg>
                                    @break
                                @case('building')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="5" y="3" width="14" height="18" rx="3" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h2m2 0h2M9 11h2m2 0h2M9 15h2m2 0h2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21v-4" /></svg>
                                    @break
                                @case('briefcase')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 6V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1" /><rect x="3" y="6" width="18" height="14" rx="3" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 11h18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 11v2h6v-2" /></svg>
                                    @break
                                @case('map')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20 3 17V4l6 3 6-3 6 3v13l-6-3-6 3Z" /><circle cx="12" cy="11" r="2.2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 13.5V20" /></svg>
                                    @break
                                @case('tag')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m20 13-7 7-11-11V2h7L20 13Z" /><circle cx="7.5" cy="7.5" r="1.5" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 11l4 4" /></svg>
                                    @break
                                @case('shield')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 3v5c0 4.9-3.1 8.9-7 10-3.9-1.1-7-5.1-7-10V6l7-3Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 12.5 11.2 14 14.8 9.8" /></svg>
                                    @break
                                @case('monitor')
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="12" rx="2.5" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 20h8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16v4" /></svg>
                                    @break
                            @endswitch
                        </div>
                        <span class="rounded-full border border-white/70 bg-white/75 px-3 py-1 text-[11px] font-semibold text-slate-600 opacity-85 transition group-hover:opacity-100">เปิดหน้าจัดการ</span>
                    </div>
                    <div class="mt-5 text-lg font-bold {{ $style['titleText'] }}">{{ $module['title'] }}</div>
                    <div class="mt-2 text-sm leading-6 text-slate-600">{{ $module['description'] }}</div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
