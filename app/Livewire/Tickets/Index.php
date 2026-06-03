<?php

namespace App\Livewire\Tickets;

use App\Repositories\TicketRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $search = '';

    public string $status = 'all';

    public string $priority = 'all';

    public string $assignment = 'all';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $sort = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'priority' => ['except' => 'all'],
        'assignment' => ['except' => 'all'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sort' => ['except' => 'latest'],
    ];

    protected $listeners = ['ticket-created' => '$refresh'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedPriority(): void
    {
        $this->resetPage();
    }

    public function updatedAssignment(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'priority', 'assignment', 'dateFrom', 'dateTo', 'sort']);
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $tickets = app(TicketRepository::class)->paginate([
            'search' => $this->search,
            'status' => $this->status,
            'priority' => $this->priority,
            'assignment' => $this->assignment,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'sort' => $this->sort,
        ], 10);

        return view('livewire.tickets.index', compact('tickets'));
    }
}
