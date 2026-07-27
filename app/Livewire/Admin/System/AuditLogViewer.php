<?php

namespace App\Livewire\Admin\System;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.admin')]
class AuditLogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $log_name = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Activity::with('causer')->latest();

        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHasMorph('causer', [\App\Domain\Auth\Models\User::class], function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        if ($this->log_name) {
            $query->where('log_name', $this->log_name);
        }

        $logs = $query->paginate(20);
        
        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('livewire.admin.system.audit-log-viewer', [
            'logs' => $logs,
            'logNames' => $logNames,
        ]);
    }
}
