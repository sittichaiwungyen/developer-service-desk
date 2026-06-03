<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-base font-semibold text-slate-900">รายการตัวอย่าง</h3>
            </div>
            <div class="p-5">
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($items as $item)
                        <div class="rounded-2xl border border-slate-200 {{ $item['tone'] ?? 'bg-slate-50' }} px-4 py-4 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/80 {{ $item['iconText'] ?? 'text-slate-600' }} ring-1 ring-white/80 shadow-sm">
                                    @switch($item['icon'] ?? 'tag')
                                        @case('users')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1" /><circle cx="10" cy="8" r="3" stroke-width="1.8" /></svg>
                                            @break
                                        @case('shield')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 3v5c0 4.9-3.1 8.9-7 10-3.9-1.1-7-5.1-7-10V6l7-3Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v5" /><circle cx="12" cy="16.5" r="0.8" fill="currentColor" stroke="none" /></svg>
                                            @break
                                        @case('building')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" /></svg>
                                            @break
                                        @case('badge')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10v18l-5-3-5 3V3Z" /></svg>
                                            @break
                                        @case('code')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 18-6-6 6-6" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 6 6 6-6 6" /></svg>
                                            @break
                                        @case('headset')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12a8 8 0 0 1 16 0v6a2 2 0 0 1-2 2h-3v-6h5" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 14v4a2 2 0 0 0 2 2h2" /></svg>
                                            @break
                                        @case('eye')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" /><circle cx="12" cy="12" r="2.5" stroke-width="1.8" /></svg>
                                            @break
                                        @case('hospital')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 7v6m-3-3h6" /></svg>
                                            @break
                                        @case('bed')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 12h16v7H4z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 12V8h6a4 4 0 0 1 4 4" /></svg>
                                            @break
                                        @case('monitor')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="12" rx="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 20h8" /></svg>
                                            @break
                                        @case('stethoscope')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3v6a4 4 0 0 0 8 0V3" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 12v2a4 4 0 0 0 8 0v-3" /><circle cx="18" cy="16" r="2" stroke-width="1.8" /></svg>
                                            @break
                                        @case('heart')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s-7-4.5-9.5-9A5.8 5.8 0 0 1 12 5.5 5.8 5.8 0 0 1 21.5 12c-2.5 4.5-9.5 9-9.5 9Z" /></svg>
                                            @break
                                        @case('chip')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="7" y="7" width="10" height="10" rx="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 3v4m6-4v4M9 17v4m6-4v4M3 9h4m-4 6h4m10-6h4m-4 6h4" /></svg>
                                            @break
                                        @case('clipboard')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 4h6a2 2 0 0 1 2 2v1H7V6a2 2 0 0 1 2-2Z" /><rect x="5" y="5" width="14" height="16" rx="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 11h6M9 15h6" /></svg>
                                            @break
                                        @case('map-pin')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s6-5.5 6-11a6 6 0 0 0-12 0c0 5.5 6 11 6 11Z" /><circle cx="12" cy="10" r="2" stroke-width="1.8" /></svg>
                                            @break
                                        @case('location')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s6-5.5 6-11a6 6 0 0 0-12 0c0 5.5 6 11 6 11Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9.2h0" /></svg>
                                            @break
                                        @case('server')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="4" width="16" height="6" rx="2" stroke-width="1.8" /><rect x="4" y="14" width="16" height="6" rx="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h0M8 17h0" /></svg>
                                            @break
                                        @case('door')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10v18H7z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 12h0" /></svg>
                                            @break
                                        @case('hardware')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="6" width="16" height="12" rx="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h8M8 14h5" /></svg>
                                            @break
                                        @case('software')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m8 7-4 5 4 5" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 7 4 5-4 5" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 6 10 18" /></svg>
                                            @break
                                        @case('network')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="6" cy="6" r="2" stroke-width="1.8" /><circle cx="18" cy="6" r="2" stroke-width="1.8" /><circle cx="12" cy="18" r="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7.5 10.5 16m7.5-9.5L13.5 16" /></svg>
                                            @break
                                        @case('key')
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="8.5" cy="8.5" r="4" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12h9l-2 2 2 2-2 2 2 2" /></svg>
                                            @break
                                    @endswitch
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">{{ $item['label'] }}</div>
                                    <div class="mt-1 text-xs text-slate-500">รายการต้นแบบสำหรับจัดการข้อมูลชุดนี้</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 shadow-sm">
            หน้านี้เป็นชุดเมนูสำหรับ master data เพื่อเตรียมต่อยอดเป็นหน้าจัดการจริงในขั้นถัดไป
        </div>
    </div>
</x-app-layout>
