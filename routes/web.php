<?php

use App\Http\Controllers\MasterController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// Tickets
Route::middleware('auth')->group(function () {
    Route::get('master', function () {
        $modules = [
            ['title' => 'ผู้ใช้', 'description' => 'จัดการบัญชีผู้ใช้งานและสิทธิ์การใช้งาน', 'route' => 'master.users', 'icon' => 'users', 'tone' => 'from-rose-200 via-rose-100 to-rose-300', 'iconBg' => 'bg-rose-300', 'iconText' => 'text-rose-800', 'titleText' => 'text-rose-800'],
            ['title' => 'แผนก', 'description' => 'จัดการแผนก/หน่วยงานภายในโรงพยาบาล', 'route' => 'master.departments', 'icon' => 'building', 'tone' => 'from-indigo-200 via-indigo-100 to-indigo-300', 'iconBg' => 'bg-indigo-300', 'iconText' => 'text-indigo-800', 'titleText' => 'text-indigo-800'],
            ['title' => 'ตำแหน่งงาน', 'description' => 'จัดการตำแหน่งงานของผู้ใช้งานในระบบ', 'route' => 'master.positions', 'icon' => 'briefcase', 'tone' => 'from-sky-200 via-sky-100 to-sky-300', 'iconBg' => 'bg-sky-300', 'iconText' => 'text-sky-800', 'titleText' => 'text-sky-800'],
            ['title' => 'สถานที่', 'description' => 'จัดการสถานที่ที่ใช้บันทึก Ticket', 'route' => 'master.locations', 'icon' => 'map', 'tone' => 'from-emerald-200 via-emerald-100 to-emerald-300', 'iconBg' => 'bg-emerald-300', 'iconText' => 'text-emerald-800', 'titleText' => 'text-emerald-800'],
            ['title' => 'หมวดหมู่งาน', 'description' => 'จัดการหมวดหมู่งานและหมวดย่อยของ Ticket', 'route' => 'master.categories', 'icon' => 'tag', 'tone' => 'from-amber-200 via-amber-100 to-amber-300', 'iconBg' => 'bg-amber-300', 'iconText' => 'text-amber-800', 'titleText' => 'text-amber-800'],
            ['title' => 'หมวดงานย่อย', 'description' => 'จัดการหมวดงานย่อยสำหรับ Ticket', 'route' => 'master.subcategories', 'icon' => 'tag', 'tone' => 'from-orange-200 via-orange-100 to-orange-300', 'iconBg' => 'bg-orange-300', 'iconText' => 'text-orange-800', 'titleText' => 'text-orange-800'],
            ['title' => 'ประเภทปัญหา', 'description' => 'จัดการประเภทปัญหาที่ใช้คัดกรอง Ticket', 'route' => 'master.issue_types', 'icon' => 'shield', 'tone' => 'from-fuchsia-200 via-fuchsia-100 to-fuchsia-300', 'iconBg' => 'bg-fuchsia-300', 'iconText' => 'text-fuchsia-800', 'titleText' => 'text-fuchsia-800'],
            ['title' => 'ระบบที่ได้รับผลกระทบ', 'description' => 'จัดการชื่อระบบที่ใช้ระบุผลกระทบ', 'route' => 'master.affected_systems', 'icon' => 'monitor', 'tone' => 'from-cyan-200 via-cyan-100 to-cyan-300', 'iconBg' => 'bg-cyan-300', 'iconText' => 'text-cyan-800', 'titleText' => 'text-cyan-800'],
            ['title' => 'Role', 'description' => 'จัดการสิทธิ์การใช้งานและชุด permission ของผู้ใช้', 'route' => 'master.roles', 'icon' => 'shield', 'tone' => 'from-slate-200 via-slate-100 to-slate-300', 'iconBg' => 'bg-slate-300', 'iconText' => 'text-slate-800', 'titleText' => 'text-slate-800'],
        ];

        return view('master.index', compact('modules'));
    })->name('master.index');

    foreach (['users', 'departments', 'positions', 'locations', 'categories', 'subcategories', 'issue_types', 'affected_systems', 'roles'] as $module) {
        Route::get("master/{$module}", function () use ($module) {
            return app(MasterController::class)->index($module);
        })->name("master.{$module}");

        Route::get("master/{$module}/create", function () use ($module) {
            return app(MasterController::class)->create($module);
        })->name("master.{$module}.create");

        Route::post("master/{$module}", function (
            \Illuminate\Http\Request $request
        ) use ($module) {
            return app(MasterController::class)->store($request, $module);
        })->name("master.{$module}.store");

        Route::get("master/{$module}/{record}/edit", function ($record) use ($module) {
            return app(MasterController::class)->edit($module, (int) $record);
        })->whereNumber('record')->name("master.{$module}.edit");

        Route::put("master/{$module}/{record}", function (
            \Illuminate\Http\Request $request,
            $record
        ) use ($module) {
            return app(MasterController::class)->update($request, $module, (int) $record);
        })->whereNumber('record')->name("master.{$module}.update");

        Route::delete("master/{$module}/{record}", function (
            \Illuminate\Http\Request $request,
            $record
        ) use ($module) {
            return app(MasterController::class)->destroy($request, $module, (int) $record);
        })->whereNumber('record')->name("master.{$module}.destroy");
    }

    Route::view('tickets', 'tickets.index')->name('tickets.index');
    Route::view('tickets/create', 'tickets.create')->name('tickets.create');
    Route::get('tickets/{ticket}', function (\Illuminate\Http\Request $request, \App\Models\Ticket $ticket) {
        $historyFilter = $request->string('history_filter')->toString() ?: 'all';

        $historiesQuery = $ticket->histories()->with('user')->latest();

        if ($historyFilter === 'status') {
            $historiesQuery->whereIn('action', ['created', 'status_changed']);
        } elseif ($historyFilter === 'assignment') {
            $historiesQuery->whereIn('action', ['created', 'assigned']);
        }

        $users = \App\Models\User::role(['Admin', 'Manager', 'Developer', 'Support'])
            ->with('roles')
            ->orderBy('name')
            ->get();

        return view('tickets.show', [
            'ticket' => $ticket,
            'users' => $users,
            'histories' => $historiesQuery->get(),
            'historyFilter' => $historyFilter,
        ]);
    })->name('tickets.show');
    Route::get('tickets/{ticket}/edit', function (\App\Models\Ticket $ticket) {
        return view('tickets.edit', [
            'ticket' => $ticket,
            'departments' => \App\Models\Department::query()->orderBy('name')->get(),
            'users' => \App\Models\User::role(['Admin', 'Manager', 'Developer', 'Support'])
                ->with('roles')
                ->orderBy('name')
                ->get(),
        ]);
    })->name('tickets.edit');
    Route::put('tickets/{ticket}', function (\Illuminate\Http\Request $request, \App\Models\Ticket $ticket) {
        $data = $request->validate([
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'requester_name' => ['nullable', 'string', 'max:255'],
            'requester_email' => ['nullable', 'email', 'max:255'],
            'requester_position' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'resolution' => ['nullable', 'string'],
            'root_cause' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'subcategory' => ['nullable', 'string', 'max:100'],
            'issue_type' => ['nullable', 'string', 'max:100'],
            'affected_system' => ['nullable', 'string', 'max:100'],
            'asset_tag' => ['nullable', 'string', 'max:100'],
            'ip_address' => ['nullable', 'string', 'max:45'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'impact_level' => ['required', 'in:low,medium,high,critical'],
            'follow_up_required' => ['sometimes', 'boolean'],
        ]);

        $data['follow_up_required'] = $request->boolean('follow_up_required');

        app(\App\Services\TicketService::class)->updateTicket($ticket, $data, $request->user());

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'บันทึกการแก้ไขสำเร็จ',
            'text' => 'Ticket '.$ticket->ticket_number.' ถูกอัปเดตเรียบร้อยแล้ว',
        ]);

        return redirect()->route('tickets.show', $ticket);
    })->name('tickets.update');
    Route::delete('tickets/{ticket}', function (\Illuminate\Http\Request $request, \App\Models\Ticket $ticket) {
        app(\App\Services\TicketService::class)->deleteTicket($ticket, $request->user());

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'ลบ Ticket สำเร็จ',
            'text' => 'Ticket '.$ticket->ticket_number.' ถูกลบแล้ว',
        ]);

        return redirect()->route('tickets.index');
    })->name('tickets.destroy');
    Route::post('tickets/{ticket}/status', function (\Illuminate\Http\Request $request, \App\Models\Ticket $ticket) {
        $data = $request->validate([
            'action' => ['required', 'string'],
        ]);

        app(\App\Services\TicketService::class)->updateStatus($ticket, $data['action'], $request->user());

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'อัปเดตสถานะสำเร็จ',
            'text' => 'Ticket '.$ticket->ticket_number.' ถูกอัปเดตเรียบร้อยแล้ว',
        ]);

        return back();
    })->name('tickets.status.update');
    Route::post('tickets/{ticket}/assign', function (\Illuminate\Http\Request $request, \App\Models\Ticket $ticket) {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = \App\Models\User::query()->findOrFail($data['user_id']);

        app(\App\Services\TicketService::class)->assignToUser($ticket, $user);

        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'มอบหมายสำเร็จ',
            'text' => 'Ticket '.$ticket->ticket_number.' ถูกมอบหมายให้ '.$user->name.' แล้ว',
        ]);

        return back();
    })->name('tickets.assign');
});
