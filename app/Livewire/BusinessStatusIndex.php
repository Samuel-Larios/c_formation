<?php

namespace App\Livewire;

use App\Models\BusinessStatus;
use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BusinessStatusExport;

class BusinessStatusIndex extends Component
{
    use WithPagination;

    public $type_of_business = '';
    public $status = '';
    public $sexe = '';
    public $promotion_id = '';
    public $totalBusinessStatuses;
    protected $paginationTheme = 'bootstrap';
    protected $pageName = 'bs_page';

    public function updatedType_of_business()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedSexe()
    {
        $this->resetPage();
    }

    public function updatedPromotion_id()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->type_of_business = '';
        $this->status = '';
        $this->sexe = '';
        $this->promotion_id = '';
        $this->resetPage();
    }

    public function exportExcel()
    {
        $fileName = 'business_statuses_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new BusinessStatusExport(
            $this->type_of_business,
            $this->status,
            $this->sexe,
            $this->promotion_id,
            'en'
        ), $fileName);
    }

    public function render()
    {
        $this->totalBusinessStatuses = BusinessStatus::count();

        $businessStatuses = BusinessStatus::with(['student', 'site'])
            ->when($this->type_of_business, function ($q) {
                $q->where('type_of_business', 'like', "%{$this->type_of_business}%");
            })
            ->when($this->status, function ($q) {
                $q->where('status', 'like', "%{$this->status}%");
            })
            ->when($this->sexe, function ($q) {
                $q->whereHas('student', function ($sq) {
                    $sq->where('sexe', 'like', "%{$this->sexe}%");
                });
            })
            ->when($this->promotion_id, function ($q) {
                $q->whereHas('student.promotions', function ($pq) {
                    $pq->where('promotions.id', $this->promotion_id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $promotions = Promotion::all();

        return view('livewire.business-status-index', compact('businessStatuses', 'promotions'));
    }
}
