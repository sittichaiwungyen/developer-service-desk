<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-sm">
                        @switch($icon)
                            @case('users')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1" /><circle cx="10" cy="8" r="3" stroke-width="1.8" /></svg>
                                @break
                            @case('building')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" /></svg>
                                @break
                            @case('briefcase')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="7" width="18" height="12" rx="2" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" /></svg>
                                @break
                            @case('map')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m8 5 8-2v16l-8 2-4-1.2V3.8L8 5Zm8-2 4 1.2v14.4L16 21" /></svg>
                                @break
                            @case('tag')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12V4h8l10 10-8 8L3 12Z" /><circle cx="7" cy="7" r="1.25" fill="currentColor" stroke="none" /></svg>
                                @break
                            @case('shield')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 3v5c0 4.9-3.1 8.9-7 10-3.9-1.1-7-5.1-7-10V6l7-3Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 12.5 11.2 14 14.8 9.8" /></svg>
                                @break
                            @case('monitor')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="12" rx="2.5" stroke-width="1.8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 20h8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16v4" /></svg>
                                @break
                        @endswitch
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('master.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12.5 4.5L7 10l5.5 5.5" /></svg>
                    <span>กลับเมนู Master</span>
                </a>
                <a href="{{ $createUrl }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 4v12M4 10h12" /></svg>
                    <span>เพิ่มข้อมูล</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <form method="GET" onsubmit="(function(){var o=document.getElementById('page-loading'); if(o){ o.classList.remove('hidden'); } else if(window.showPageLoading){ window.showPageLoading(); } })()" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid gap-4 lg:grid-cols-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">ค้นหา</label>
                    <input name="search" value="{{ $filters['search'] ?? '' }}" type="text" class="input-modern" placeholder="พิมพ์ชื่อ รหัส หรือรายละเอียด" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">สถานะ</label>
                    <select name="status" class="select-modern">
                        @foreach ($filterOptions['status'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? 'active') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">การใช้งาน</label>
                    <select name="usage" class="select-modern">
                        @foreach ($filterOptions['usage'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['usage'] ?? 'all') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="flex items-end gap-3">
                    <button type="submit" onclick="(function(){var o=document.getElementById('page-loading'); if(o){ o.classList.remove('hidden'); } else if(window.showPageLoading){ window.showPageLoading(); } })()" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">กรอง</button>
                    <a href="{{ route("master.{$module}") }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">ล้าง</a>
                </div>
            </div>

            @if ($module === 'users')
                <div class="mt-4 grid gap-4 lg:grid-cols-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">แผนก</label>
                        <input name="department_id" list="department-filter-options" value="{{ $departmentFilterValue ?? '' }}" type="text" class="input-modern" placeholder="ทั้งหมด / พิมพ์หรือเลือกชื่อแผนก" />
                        <datalist id="department-filter-options">
                            @foreach ($departments as $department)
                                <option value="{{ $department->name }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">ตำแหน่ง</label>
                        <input name="position_id" list="position-filter-options" value="{{ $positionFilterValue ?? '' }}" type="text" class="input-modern" placeholder="ทั้งหมด / พิมพ์หรือเลือกชื่อตำแหน่ง" />
                        <datalist id="position-filter-options">
                            @foreach ($positions as $position)
                                <option value="{{ $position->name }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
            @endif
        </form>

        <div class="grid gap-4" style="grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));">
            @foreach ($summaryCards as $card)
                @php
                    $toneClasses = [
                        'sky' => ['card' => 'bg-gradient-to-br from-sky-200 via-white to-cyan-100', 'label' => 'bg-sky-200', 'labelText' => 'text-sky-800', 'value' => 'text-sky-950', 'accent' => 'bg-sky-300'],
                        'emerald' => ['card' => 'bg-gradient-to-br from-emerald-200 via-white to-teal-100', 'label' => 'bg-emerald-200', 'labelText' => 'text-emerald-800', 'value' => 'text-emerald-950', 'accent' => 'bg-emerald-300'],
                        'violet' => ['card' => 'bg-gradient-to-br from-violet-200 via-white to-fuchsia-100', 'label' => 'bg-violet-200', 'labelText' => 'text-violet-800', 'value' => 'text-violet-950', 'accent' => 'bg-violet-300'],
                        'rose' => ['card' => 'bg-gradient-to-br from-rose-200 via-white to-pink-100', 'label' => 'bg-rose-200', 'labelText' => 'text-rose-800', 'value' => 'text-rose-950', 'accent' => 'bg-rose-300'],
                        'amber' => ['card' => 'bg-gradient-to-br from-amber-200 via-white to-yellow-100', 'label' => 'bg-amber-200', 'labelText' => 'text-amber-800', 'value' => 'text-amber-950', 'accent' => 'bg-amber-300'],
                        'orange' => ['card' => 'bg-gradient-to-br from-orange-200 via-white to-amber-100', 'label' => 'bg-orange-200', 'labelText' => 'text-orange-800', 'value' => 'text-orange-950', 'accent' => 'bg-orange-300'],
                        'cyan' => ['card' => 'bg-gradient-to-br from-cyan-200 via-white to-sky-100', 'label' => 'bg-cyan-200', 'labelText' => 'text-cyan-800', 'value' => 'text-cyan-950', 'accent' => 'bg-cyan-300'],
                        'indigo' => ['card' => 'bg-gradient-to-br from-indigo-200 via-white to-sky-100', 'label' => 'bg-indigo-200', 'labelText' => 'text-indigo-800', 'value' => 'text-indigo-950', 'accent' => 'bg-indigo-300'],
                        'slate' => ['card' => 'bg-gradient-to-br from-slate-200 via-white to-slate-100', 'label' => 'bg-slate-200', 'labelText' => 'text-slate-700', 'value' => 'text-slate-950', 'accent' => 'bg-slate-300'],
                    ][$card['tone']] ?? ['card' => 'bg-gradient-to-br from-slate-200 via-white to-slate-100', 'label' => 'bg-slate-200', 'labelText' => 'text-slate-700', 'value' => 'text-slate-950', 'accent' => 'bg-slate-300'];
                @endphp
                <div class="group relative overflow-hidden rounded-[2rem] border border-white/70 p-5 shadow-[0_18px_36px_-24px_rgba(15,23,42,0.35)] transition hover:-translate-y-0.5 hover:shadow-[0_24px_48px_-28px_rgba(15,23,42,0.42)] {{ $toneClasses['card'] }}">
                    <div class="pointer-events-none absolute inset-0 opacity-60 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.9),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(255,255,255,0.5),transparent_28%)]"></div>
                    <div class="pointer-events-none absolute left-0 top-0 h-1.5 w-full {{ $toneClasses['accent'] }}"></div>
                    <div class="relative inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $toneClasses['label'] }} {{ $toneClasses['labelText'] }}">{{ $card['label'] }}</div>
                    <div class="mt-3 text-3xl font-bold tracking-tight {{ $toneClasses['value'] }}">{{ $card['value'] }}</div>
                    <div class="mt-2 text-sm leading-6 text-slate-500">{{ $card['note'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">ลำดับ</th>
                            @foreach ($columns as $column)
                                <th class="px-5 py-4 font-semibold">{{ $column['label'] }}</th>
                            @endforeach
                            <th class="px-5 py-4 font-semibold">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($rows as $row)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4 align-top font-semibold text-slate-500">{{ $row['order'] }}</td>
                                @foreach ($columns as $column)
                                    <td class="px-5 py-4 align-top text-slate-700">
                                        @php($value = $row[$column['key']] ?? '-')
                                        @if (is_array($value))
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($value as $badge)
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ $badge }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span>{{ $value ?: '-' }}</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route("master.{$module}.edit", $row['id']) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 transition hover:bg-sky-100">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.5 3.5l3 3L8 15H5v-3l8.5-8.5Z" /></svg>
                                            แก้ไข
                                        </a>
                                        <form method="POST" action="{{ route("master.{$module}.destroy", $row['id']) }}" data-swal-confirm data-swal-confirm-title="ยืนยันการลบ" data-swal-confirm-text="คุณต้องการลบรายการนี้หรือไม่" data-swal-confirm-button="ลบ" class="inline-flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h6m-5 0v7m4-7v7M4 5h12M6 5l1-2h6l1 2m-8 0h8l-.75 10.5A2 2 0 0 1 10.26 17H9.74a2 2 0 0 1-1.99-1.5L7 5Z" /></svg>
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 2 }}" class="px-5 py-14 text-center text-sm text-slate-500">
                                    {{ $emptyText }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</x-app-layout>