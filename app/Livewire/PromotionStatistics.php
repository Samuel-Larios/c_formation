<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Promotion;
use App\Models\Student;
use Illuminate\Support\Collection;

class PromotionStatistics extends Component
{
    public $promotions;
    public $selectedPromotion = null;
    public $expectedStudents = 0;
    public $currentStudentsCount = 0;
    public $lastFivePromotionsData = [];

    public function mount()
    {
        $this->promotions = Promotion::orderBy('num_promotion', 'desc')->get();
        if ($this->promotions->isNotEmpty()) {
            $this->selectedPromotion = $this->promotions->first()->id;
            $this->updateStudentCounts();
            $this->prepareLastFivePromotionsData();
        }
    }

    public function updatedSelectedPromotion()
    {
        $this->updateStudentCounts();
    }

    public function updatedExpectedStudents()
    {
        // No specific action needed here, but could be used for validation or other logic
    }

    protected function updateStudentCounts()
    {
        if ($this->selectedPromotion) {
            $this->currentStudentsCount = Student::whereHas('promotions', function ($query) {
                $query->where('promotion_id', $this->selectedPromotion);
            })->count();
        } else {
            $this->currentStudentsCount = 0;
        }
    }

    protected function prepareLastFivePromotionsData()
    {
        $lastFivePromotions = $this->promotions->take(5);
        $data = [];
        foreach ($lastFivePromotions as $promotion) {
            $count = Student::whereHas('promotions', function ($query) use ($promotion) {
                $query->where('promotion_id', $promotion->id);
            })->count();
            $data[] = [
                'promotion' => $promotion->num_promotion,
                'student_count' => $count,
            ];
        }
        $this->lastFivePromotionsData = $data;
    }

    public function render()
    {
        return view('livewire.promotion-statistics', [
            'promotions' => $this->promotions,
            'currentStudentsCount' => $this->currentStudentsCount,
            'lastFivePromotionsData' => $this->lastFivePromotionsData,
        ]);
    }
}
