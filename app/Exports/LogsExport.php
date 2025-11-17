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
    private $logs;
    private $includeImages;

    public function __construct(Request $request, $logs = null, $includeImages = true)
    {
        $this->userId = Auth::id();
        $this->search = $request->get('search');
        $this->startDate = $request->get('start_date');
        $this->endDate = $request->get('end_date');
        $this->isAdmin = Auth::user()->email === 'admin@example.com';
        $this->logs = $logs;
        $this->includeImages = $includeImages;
    }

    public function export()
    {
        // Use provided logs if available (current page only), otherwise get all
        if ($this->logs) {
            $logs = $this->logs;
        } else {
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
        }

        // Encode gambar ke base64 jika diminta
        if ($this->includeImages) {
            foreach ($logs as $log) {
                if ($log->file_bukti) {
                    $decoded = json_decode($log->file_bukti, true) ?: [];
                    $fileData = isset($decoded['file']) ? $decoded['file'] : null;

                    if ($fileData) {
                        $filePath = storage_path('app/public/' . $fileData);
                        $fileExt = pathinfo($fileData, PATHINFO_EXTENSION);

                        if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']) && file_exists($filePath)) {
                            $imageData = file_get_contents($filePath);
                            $base64 = base64_encode($imageData);
                            $mimeType = 'image/' . ($fileExt === 'jpg' ? 'jpeg' : $fileExt);
                            $log->image_base64 = 'data:' . $mimeType . ';base64,' . $base64;
                        }
                    }
                }
            }
        }

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
