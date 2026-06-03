<?php

namespace App\Livewire\Tickets;

use App\Models\Department;
use App\Models\AffectedSystem;
use App\Models\Category;
use App\Models\IssueType;
use App\Models\Location;
use App\Models\Position;
use App\Models\Subcategory;
use App\Services\TicketService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateTicket extends Component
{
    use WithFileUploads;

    public $subject;
    public $description;
    public $resolution;
    public $root_cause;
    public $priority = 'medium';
    public $impact_level = 'medium';
    public $department_id = null;
    public $department_name;
    public $requester_name;
    public $requester_email;
    public $requester_position;
    public $contact_phone;
    public $location;
    public $category;
    public $subcategory;
    public $issue_type;
    public $affected_system;
    public $asset_tag;
    public $ip_address;
    public $follow_up_required = false;
    public array $attachments = [];

    protected function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resolution' => 'nullable|string',
            'root_cause' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
            'impact_level' => 'required|in:low,medium,high,critical',
            'department_name' => ['nullable', 'string', 'max:255', Rule::exists('departments', 'name')->whereNull('deleted_at')],
            'requester_name' => 'nullable|string|max:255',
            'requester_email' => 'nullable|email|max:255',
            'requester_position' => ['nullable', 'string', 'max:255', Rule::exists('positions', 'name')->whereNull('deleted_at')],
            'contact_phone' => 'nullable|string|max:50',
            'location' => ['nullable', 'string', 'max:255', Rule::exists('locations', 'name')->whereNull('deleted_at')],
            'category' => ['nullable', 'string', 'max:100', Rule::exists('categories', 'name')->whereNull('deleted_at')],
            'subcategory' => ['nullable', 'string', 'max:100', Rule::exists('subcategories', 'name')->whereNull('deleted_at')],
            'issue_type' => ['nullable', 'string', 'max:100', Rule::exists('issue_types', 'name')->whereNull('deleted_at')],
            'affected_system' => ['nullable', 'string', 'max:100', Rule::exists('affected_systems', 'name')->whereNull('deleted_at')],
            'asset_tag' => 'nullable|string|max:100',
            'ip_address' => 'nullable|string|max:45',
            'attachments.*' => 'file|max:10240',
        ];
    }

    public function createTicket()
    {
        $this->validate();

        $data = $this->only([
            'subject', 'description', 'resolution', 'root_cause', 'priority', 'impact_level',
            'department_name', 'requester_name', 'requester_email', 'requester_position',
            'contact_phone', 'location', 'category', 'subcategory', 'issue_type',
            'affected_system', 'asset_tag', 'ip_address', 'follow_up_required',
        ]);
        $data['department_id'] = $this->department_name
            ? Department::query()->where('name', $this->department_name)->value('id')
            : null;
        $data['requester_id'] = Auth::id();

        $ticket = app(TicketService::class)->createTicket($data);

        foreach ($this->attachments as $attachment) {
            $path = $attachment->store("tickets/{$ticket->id}", 'public');

            $ticket->attachments()->create([
                'filename' => $attachment->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $attachment->getClientMimeType(),
                'size' => $attachment->getSize(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        $this->reset([
            'subject', 'description', 'resolution', 'root_cause', 'priority', 'impact_level',
            'department_id', 'department_name', 'requester_name', 'requester_email', 'requester_position',
            'contact_phone', 'location', 'category', 'subcategory', 'issue_type',
            'affected_system', 'asset_tag', 'ip_address', 'follow_up_required', 'attachments',
        ]);

        $this->dispatch('ticket-created', ticketNumber: $ticket->ticket_number);
        $this->dispatch('notify', message: 'Ticket created: '.$ticket->ticket_number);
        session()->flash('sweetalert', [
            'icon' => 'success',
            'title' => 'บันทึกสำเร็จ',
            'text' => 'Ticket '.$ticket->ticket_number.' ถูกบันทึกเรียบร้อยแล้ว',
        ]);

        return redirect()->route('tickets.index');
    }

    public function render()
    {
        return view('livewire.tickets.create-ticket', [
            'departments' => Department::query()->orderBy('name')->get(['name']),
            'positions' => Position::query()->orderBy('name')->get(['name']),
            'locations' => Location::query()->orderBy('name')->get(['name']),
            'categories' => Category::query()->orderBy('name')->get(['name']),
            'subcategories' => Subcategory::query()->orderBy('name')->get(['name']),
            'issueTypes' => IssueType::query()->orderBy('name')->get(['name']),
            'affectedSystems' => AffectedSystem::query()->orderBy('name')->get(['name']),
        ]);
    }
}
