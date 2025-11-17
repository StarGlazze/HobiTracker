<?php

namespace App\Exports;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LogsExport
{
    private $userId;
    private $search;
    private $startDate;
    private $endDate;
    private $isAdmin;

    public function __construct(Request $request)
    {
        $this->userId = Auth::id();
        $this->search = $request->get('search');
        $this->startDate = $request->get('start_date');
        $this->endDate = $request->get('end_date');
        $this->isAdmin = Auth::user()->email === 'admin@example.com';
    }

    public function export()
    {
        $query = LogAktivitas::with(['aktivitas.target.hobi']);

        if (!$this->isAdmin) {
            $query->where('user_id', $this->userId);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('aktivitas', fn($sub) => $sub->where('nama_aktivitas', 'like', "%{$this->search}%"))
                    ->orWhere('catatan', 'like', "%{$this->search}%");
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'logs' => $logs,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'generatedDate' => now()->format('d F Y H:i')
        ];

        $pdf = Pdf::loadView('admin.logs_pdf', $data);
        $filename = 'logs_aktivitas_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }
}
