<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TicketRepository
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Ticket::query()->with('assignedTo');

        $this->applySearch($query, $filters['search'] ?? '');
        $this->applyStatusFilter($query, $filters['status'] ?? 'all');
        $this->applyPriorityFilter($query, $filters['priority'] ?? 'all');
        $this->applyAssignmentFilter($query, $filters['assignment'] ?? 'all');
        $this->applyDateRangeFilter($query, $filters['dateFrom'] ?? '', $filters['dateTo'] ?? '');

        return $this->applySorting($query, $filters['sort'] ?? 'latest')->paginate($perPage);
    }

    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    protected function applySearch(Builder $query, string $search): void
    {
        $search = trim($search);

        if ($search === '') {
            return;
        }

        $query->where(function (Builder $builder) use ($search) {
            $builder->where('ticket_number', 'like', '%' . $search . '%')
                ->orWhere('subject', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('requester_name', 'like', '%' . $search . '%')
                ->orWhere('contact_phone', 'like', '%' . $search . '%');
        });
    }

    protected function applyStatusFilter(Builder $query, string $status): void
    {
        if ($status === 'all' || $status === '') {
            return;
        }

        $query->where('status', $status);
    }

    protected function applyPriorityFilter(Builder $query, string $priority): void
    {
        if ($priority === 'all' || $priority === '') {
            return;
        }

        $query->where('priority', $priority);
    }

    protected function applyAssignmentFilter(Builder $query, string $assignment): void
    {
        if ($assignment === 'all' || $assignment === '') {
            return;
        }

        if ($assignment === 'unassigned') {
            $query->whereNull('assigned_to');

            return;
        }

        if ($assignment === 'assigned') {
            $query->whereNotNull('assigned_to');

            return;
        }

        if ($assignment === 'mine' && auth()->check()) {
            $query->where('assigned_to', auth()->id());
        }
    }

    protected function applyDateRangeFilter(Builder $query, string $dateFrom, string $dateTo): void
    {
        $dateFrom = trim($dateFrom);
        $dateTo = trim($dateTo);

        if ($dateFrom === '' && $dateTo === '') {
            return;
        }

        if ($dateFrom !== '' && $dateTo !== '') {
            $query->whereBetween('created_at', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59',
            ]);

            return;
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);

            return;
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }
    }

    protected function applySorting(Builder $query, string $sort): Builder
    {
        return match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc'),
            'priority' => $query->orderByRaw("CASE priority WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END")->orderByDesc('created_at'),
            default => $query->latest(),
        };
    }
}
