<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\ImageDicom;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalPatients;
    public $totalConsultations;
    public $totalDicomImages;
    public $recentNotifications;
    public $consultationsPerWeek = [];

    public function mount()
    {
        $this->totalPatients = Patient::count();
        $this->totalConsultations = Consultation::count();
        $this->totalDicomImages = ImageDicom::count();

        $this->recentNotifications = Notification::orderBy('dateEnvoi', 'desc')
            ->limit(5)
            ->get();

        $this->loadConsultationsPerWeek();
    }

    public function loadConsultationsPerWeek()
    {
        $startDate = Carbon::now()->subWeeks(8)->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $consultations = Consultation::select(
            DB::raw("YEARWEEK(created_at, 1) as yearweek"),
            DB::raw("COUNT(*) as count")
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('yearweek')
        ->orderBy('yearweek')
        ->get();

        $data = [];
        $period = new \DatePeriod(
            $startDate,
            \DateInterval::createFromDateString('1 week'),
            $endDate
        );

        foreach ($period as $date) {
            $yearweek = $date->format('oW'); // ISO-8601 year and week number
            $data[$yearweek] = 0;
        }

        foreach ($consultations as $consultation) {
            $data[$consultation->yearweek] = $consultation->count;
        }

        $this->consultationsPerWeek = $data;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
