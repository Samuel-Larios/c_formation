<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Promotion;
use App\Models\Student;
use App\Models\JobCreation;
use Illuminate\Support\Collection;

class PromotionStatistics extends Component
{
    public $promotions;
    public $selectedPromotion = null;
    public $expectedStudents = 0;
    public $currentStudentsCount = 0;
    public $lastFivePromotionsData = [];

    // New properties for job creation statistics
    public $jobCreationsCount = 0;
    public $expectedJobCreations = 0;
    public $jobCreationsPercentage = 0;
    public $histogramData = [];

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
        $this->prepareLastFivePromotionsData();
        $this->dispatch('promotion-changed');
    }

    public function updatedExpectedStudents()
    {
        // No specific action needed here, but could be used for validation or other logic
    }

    protected function updateStudentCounts()
    {
        if ($this->selectedPromotion) {
            // Get all students from the selected promotion regardless of site
            $this->currentStudentsCount = Student::whereHas('promotions', function ($query) {
                $query->where('promotion_id', $this->selectedPromotion);
            })->count();

            // Count job creations for students in this promotion
            $this->jobCreationsCount = JobCreation::whereHas('student.promotions', function ($query) {
                $query->where('promotion_id', $this->selectedPromotion);
            })->count();

            // Calculate expected job creations (70% of students)
            $this->expectedJobCreations = intval($this->currentStudentsCount * 0.7);

            // Calculate percentage
            if ($this->expectedJobCreations > 0) {
                $this->jobCreationsPercentage = round(($this->jobCreationsCount / $this->expectedJobCreations) * 100, 2);
            } else {
                $this->jobCreationsPercentage = 0;
            }

            // Prepare histogram data
            $this->prepareHistogramData();
        } else {
            $this->currentStudentsCount = 0;
            $this->jobCreationsCount = 0;
            $this->expectedJobCreations = 0;
            $this->jobCreationsPercentage = 0;
            $this->histogramData = [];
        }
    }

    protected function prepareHistogramData()
    {
        if ($this->selectedPromotion) {
            // Get all students in the promotion
            $students = Student::whereHas('promotions', function ($query) {
                $query->where('promotion_id', $this->selectedPromotion);
            })->with('jobCreations')->get();

            // Count students with and without job creations
            $studentsWithJobs = 0;
            $studentsWithoutJobs = 0;

            foreach ($students as $student) {
                if ($student->jobCreations->count() > 0) {
                    $studentsWithJobs++;
                } else {
                    $studentsWithoutJobs++;
                }
            }

            $this->histogramData = [
                'students_with_jobs' => $studentsWithJobs,
                'students_without_jobs' => $studentsWithoutJobs,
                'total_students' => $this->currentStudentsCount,
                'job_creations_count' => $this->jobCreationsCount,
                'expected_job_creations' => $this->expectedJobCreations
            ];
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
            'jobCreationsCount' => $this->jobCreationsCount,
            'expectedJobCreations' => $this->expectedJobCreations,
            'jobCreationsPercentage' => $this->jobCreationsPercentage,
            'histogramData' => $this->histogramData,
        ]);
    }
}
