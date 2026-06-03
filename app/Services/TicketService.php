<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Repositories\TicketRepository;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TicketService
{
    protected TicketRepository $repo;

    private const STATUS_TRANSITIONS = [
        'assign_to_me' => 'assigned',
        'start_work' => 'in_progress',
        'waiting_user' => 'waiting_user',
        'testing' => 'testing',
        'done' => 'done',
        'closed' => 'closed',
        'cancelled' => 'cancelled',
    ];

    private const STATUS_FLOW = [
        'new',
        'assigned',
        'in_progress',
        'waiting_user',
        'testing',
        'done',
        'closed',
    ];

    public function __construct(TicketRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createTicket(array $data)
    {
        $data['uuid'] = Str::uuid()->toString();
        $data['ticket_number'] = $this->generateTicketNumber();

        $ticket = $this->repo->create($data);

        $this->recordHistory($ticket, null, 'created', [
            'status' => ['from' => null, 'to' => $ticket->status],
        ]);

        return $ticket;
    }

    public function updateTicket(Ticket $ticket, array $data, ?User $user = null): Ticket
    {
        $before = $ticket->only(array_keys($data));

        $ticket->fill($data);
        $ticket->save();

        $changes = [];

        foreach ($data as $field => $value) {
            if (($before[$field] ?? null) !== $value) {
                $changes[$field] = [
                    'from' => $before[$field] ?? null,
                    'to' => $value,
                ];
            }
        }

        if ($changes !== []) {
            $this->recordHistory($ticket, $user, 'updated', $changes);
        }

        return $ticket->refresh();
    }

    public function deleteTicket(Ticket $ticket, ?User $user = null): void
    {
        $this->recordHistory($ticket, $user, 'deleted', [
            'status' => ['from' => $ticket->status, 'to' => 'deleted'],
        ]);

        $ticket->delete();
    }

    public function updateStatus(Ticket $ticket, string $action, ?User $user = null): Ticket
    {
        if ($action === 'reverse_status') {
            return $this->reverseStatus($ticket);
        }

        if (! array_key_exists($action, self::STATUS_TRANSITIONS)) {
            throw ValidationException::withMessages([
                'action' => 'สถานะที่เลือกไม่ถูกต้อง',
            ]);
        }

        $status = self::STATUS_TRANSITIONS[$action];
        $payload = ['status' => $status];

        if ($action === 'assign_to_me') {
            if (! $user) {
                throw ValidationException::withMessages([
                    'action' => 'ไม่พบผู้ใช้งานสำหรับมอบหมายงาน',
                ]);
            }

            $payload['assigned_to'] = $user->id;
        }

        $before = $ticket->status;
        $ticket->update($payload);

        $this->recordHistory($ticket, $user, 'status_changed', [
            'status' => ['from' => $before, 'to' => $status],
            'action' => $action,
        ]);

        return $ticket->refresh();
    }

    public function assignToUser(Ticket $ticket, User $user): Ticket
    {
        $beforeAssigned = $ticket->assigned_to;
        $beforeStatus = $ticket->status;
        $ticket->update([
            'assigned_to' => $user->id,
            'status' => $ticket->status === 'new' ? 'assigned' : $ticket->status,
        ]);

        $this->recordHistory($ticket, $user, 'assigned', [
            'assigned_to' => ['from' => $beforeAssigned, 'to' => $user->id],
            'status' => ['from' => $beforeStatus, 'to' => $ticket->status],
        ]);

        return $ticket->refresh();
    }

    protected function reverseStatus(Ticket $ticket): Ticket
    {
        $currentIndex = array_search($ticket->status, self::STATUS_FLOW, true);

        if ($currentIndex === false || $currentIndex === 0) {
            throw ValidationException::withMessages([
                'action' => 'ไม่สามารถย้อนสถานะได้',
            ]);
        }

        $ticket->update([
            'status' => self::STATUS_FLOW[$currentIndex - 1],
        ]);

        return $ticket->refresh();
    }

    protected function generateTicketNumber()
    {
        $attempt = 0;
        do {
            $attempt++;
            $ticketNumber = 'T'.time().strtoupper(substr(Str::random(4), 0, 4));
            $exists = Ticket::where('ticket_number', $ticketNumber)->exists();

            if ($attempt > 10) {
                throw new \RuntimeException('Unable to generate unique ticket number after multiple attempts');
            }
        } while ($exists);

        return $ticketNumber;
    }

    protected function recordHistory(Ticket $ticket, ?User $user, string $action, array $changes): void
    {
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user?->id,
            'action' => $action,
            'changes' => $changes,
        ]);
    }
}
