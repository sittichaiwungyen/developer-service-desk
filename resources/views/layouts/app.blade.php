<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.12/dist/trix.min.css">
        <script src="https://cdn.jsdelivr.net/npm/trix@2.1.12/dist/trix.umd.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen">
            <div class="fixed inset-0 -z-10 bg-slate-100"></div>

            <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95">
                <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm lg:hidden">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 text-white shadow-sm">
                                <span class="text-sm font-bold">DS</span>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">Hospital IT</div>
                                <div class="text-sm font-bold text-slate-900">Developer Service Desk</div>
                            </div>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-xs font-semibold text-white">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="hidden text-left sm:block">
                                        <div class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                                        <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile')" wire:navigate>โปรไฟล์</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-start"><x-dropdown-link>ออกจากระบบ</x-dropdown-link></button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            <div class="mx-auto flex max-w-[1920px]">
                <aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-white lg:block">
                    <div class="flex h-[calc(100vh-4rem)] flex-col sticky top-16">
                        <div class="border-b border-slate-200 px-5 py-5">
                            <div class="rounded-2xl bg-slate-900 px-4 py-4 text-white">
                                <p class="mt-2 text-sm text-slate-300">ระบบ Service Desk, Work Log, Project และ Report</p>
                            </div>
                        </div>
                        <nav class="flex-1 space-y-1 px-3 py-4 text-sm font-medium">
                            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>🏠</span><span>แดชบอร์ด</span></a>
                            <a href="{{ route('tickets.index') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('tickets.index') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>🎫</span><span>Tickets</span></a>
                            <a href="{{ route('master.index') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('master.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>🗂️</span><span>Master Data</span></a>
                            <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('profile') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>👤</span><span>โปรไฟล์</span></a>
                        </nav>
                    </div>
                </aside>

                <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden" style="display:none;"></div>

                <aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full bg-white shadow-xl transition-transform duration-300 lg:hidden" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                    <div class="flex h-full flex-col pt-16">
                        <div class="border-b border-slate-200 px-5 py-5">
                            <div class="rounded-2xl bg-slate-900 px-4 py-4 text-white">
                                <p class="text-sm text-slate-300">ระบบ Service Desk, Work Log, Project และ Report</p>
                            </div>
                        </div>
                        <nav class="flex-1 space-y-1 px-3 py-4 text-sm font-medium">
                            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>🏠</span><span>แดชบอร์ด</span></a>
                            <a href="{{ route('tickets.index') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('tickets.index') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>🎫</span><span>Tickets</span></a>
                            <a href="{{ route('master.index') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('master.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>🗂️</span><span>Master Data</span></a>
                            <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-3 rounded-xl px-4 py-3 {{ request()->routeIs('profile') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}"><span>👤</span><span>โปรไฟล์</span></a>
                        </nav>
                    </div>
                </aside>

                <main class="min-w-0 flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                    @if (isset($header))
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts
        @if (session('sweetalert'))
            <script>
                window.addEventListener('load', function () {
                    const payload = @json(session('sweetalert'));
                    if (window.Swal) {
                        window.Swal.fire({
                            icon: payload.icon ?? 'success',
                            title: payload.title ?? 'สำเร็จ',
                            text: payload.text ?? '',
                            confirmButtonText: 'ตกลง',
                        });
                    }
                });
            </script>
        @endif
        <div id="page-loading" class="fixed inset-0 z-50 hidden flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative z-10 flex transform flex-col items-center gap-4 rounded-2xl bg-white/95 px-6 py-6 text-center shadow-2xl transition-all">
                <div class="flex items-center justify-center">
                    <svg class="h-12 w-12 animate-spin text-sky-600" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="25" cy="25" r="20" stroke="currentColor" stroke-opacity="0.15" stroke-width="6"></circle>
                        <path d="M45 25a20 20 0 0 1-20 20" stroke="currentColor" stroke-width="6" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="text-lg font-semibold text-slate-800">กำลังโหลด...</div>
                <div class="text-sm text-slate-500">กรุณารอสักครู่</div>
            </div>
        </div>

        <script>
            (function () {
                var loading = document.getElementById('page-loading');
                function showLoading() {
                    if (window.__DEBUG_LOADING) console.debug('[loading] showLoading called');
                    if (loading) loading.classList.remove('hidden');
                }
                function hideLoading() {
                    if (window.__DEBUG_LOADING) console.debug('[loading] hideLoading called');
                    if (loading) loading.classList.add('hidden');
                }

                // Wire interactions using delegation and keep datalist wiring up-to-date
                window.addEventListener('DOMContentLoaded', function () {
                    // SweetAlert confirm handling (per-form)
                    function setupSwalForms() {
                        document.querySelectorAll('form[data-swal-confirm]').forEach(function (form) {
                            if (form.__swalBound) return; // avoid double-binding
                            form.__swalBound = true;

                            form.addEventListener('submit', function (event) {
                                if (form.dataset.confirmed === '1') {
                                    form.dataset.confirmed = '0';
                                    return;
                                }

                                event.preventDefault();

                                const title = form.dataset.swalConfirmTitle || 'ยืนยันการดำเนินการ';
                                const text = form.dataset.swalConfirmText || 'คุณต้องการดำเนินการนี้หรือไม่';
                                const confirmText = form.dataset.swalConfirmButton || 'ยืนยัน';
                                const cancelText = form.dataset.swalCancelButton || 'ยกเลิก';

                                if (!window.Swal) {
                                    if (window.confirm(`${title}\n${text}`)) {
                                        showLoading();
                                        form.dataset.confirmed = '1';
                                        form.submit();
                                    }

                                    return;
                                }

                                window.Swal.fire({
                                    icon: form.dataset.swalConfirmIcon || 'warning',
                                    title: title,
                                    text: text,
                                    showCancelButton: true,
                                    confirmButtonText: confirmText,
                                    cancelButtonText: cancelText,
                                    confirmButtonColor: '#dc2626',
                                    cancelButtonColor: '#64748b',
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        showLoading();
                                        form.dataset.confirmed = '1';
                                        form.submit();
                                    }
                                });
                            });
                        });
                    }

                    // Datalist wiring
                    function wireDatalists() {
                        document.querySelectorAll('input[list][data-target]').forEach(function (input) {
                            if (input.__datalistBound) return;
                            input.__datalistBound = true;

                            var list = document.getElementById(input.getAttribute('list'));
                            var target = document.querySelector(input.dataset.target);
                            if (!list || !target) return;

                            function sync() {
                                var val = input.value;
                                var match = Array.from(list.options).find(function (o) { return o.value === val; });
                                if (match && match.dataset && match.dataset.id) {
                                    target.value = match.dataset.id;
                                } else if (/^\d+$/.test(val)) {
                                    target.value = val;
                                } else {
                                    target.value = '';
                                }
                            }

                            input.addEventListener('input', sync);
                            input.addEventListener('blur', sync);
                            var parentForm = input.closest('form');
                            if (parentForm) parentForm.addEventListener('submit', sync);
                        });
                    }

                    setupSwalForms();
                    wireDatalists();

                    // Delegated click handler for anchors
                    document.addEventListener('click', function (e) {
                        var a = e.target.closest && e.target.closest('a[href]');
                        if (!a) return;
                        // Skip explicit opt-out
                        if (a.dataset.noLoading === '1' || a.hasAttribute('data-no-loading')) return;
                        var href = a.getAttribute('href') || '';
                        if (!href) return;
                        if (href.startsWith('http') || href.startsWith('/')) {
                            if (a.target === '_blank' || e.ctrlKey || e.metaKey) return;
                            showLoading();
                        }
                    }, true);

                    // Ensure clicks on submit buttons show loading (covers first-click cases)
                    document.addEventListener('click', function (e) {
                        var btn = e.target.closest && e.target.closest('button[type="submit"], input[type="submit"]');
                        if (!btn) return;
                        var form = btn.form || btn.closest('form');
                        if (!form) return;
                        if (window.__DEBUG_LOADING) console.debug('[loading] submit-button click', { button: btn, form: form });
                        // If form handled by SweetAlert, skip — it will show loading after confirm
                        if (form.dataset.swalConfirm) return;
                        // Otherwise show loading immediately
                        showLoading();
                    }, true);

                    // Delegated submit handler for non-swal forms
                    document.addEventListener('submit', function (e) {
                        var form = e.target;
                        if (!form || !(form instanceof HTMLFormElement)) return;
                        if (form.dataset.swalConfirm) return; // swal-managed
                        showLoading();
                    }, true);

                    // Livewire: re-run wiring after updates and wire hooks for loading
                    if (window.Livewire) {
                        try {
                            Livewire.hook('message.sent', showLoading);
                            Livewire.hook('message.processed', function () { hideLoading(); wireDatalists(); setupSwalForms(); });
                            Livewire.hook('message.failed', hideLoading);
                        } catch (e) {
                            // ignore
                        }
                    }

                    window.addEventListener('pageshow', function () { hideLoading(); });
                    // expose helpers for inline calls
                    window.showPageLoading = showLoading;
                    window.hidePageLoading = hideLoading;
                });
            })();
        </script>
    </body>
</html>
