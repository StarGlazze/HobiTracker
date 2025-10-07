<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Log Aktivitas</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4e73df;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4e73df;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fc;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-box p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4e73df;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #1cc88a;
            color: white;
        }
        .badge-danger {
            background-color: #e74a3b;
            color: white;
        }
        .badge-warning {
            background-color: #f6c23e;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Log Aktivitas</h1>
        <p>Riwayat Aktivitas Hobi</p>
    </div>

    <div class="info-box">
        <p><strong>Tanggal Generate:</strong> {{ $generatedDate }}</p>
        @if($startDate && $endDate)
            <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        @endif
        <p><strong>Total Data:</strong> {{ $logs->count() }} aktivitas</p>
    </div>

    <!-- Tabel Aktivitas -->
    @forelse($logs as $index => $log)
        <div style="margin-bottom: 20px; page-break-inside: avoid;">
            <table style="margin-bottom: 10px;">
                <tr>
                    <td style="width: 100px; font-weight: bold; background: #f8f9fc;">Tanggal</td>
                    <td>{{ $log->created_at->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background: #f8f9fc;">Aktivitas</td>
                    <td>{{ $log->aktivitas->nama_aktivitas }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background: #f8f9fc;">Hobi</td>
                    <td>{{ $log->aktivitas->hobi->nama_hobi }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background: #f8f9fc;">Durasi</td>
                    <td>{{ $log->aktivitas->durasi_menit }} Menit</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background: #f8f9fc;">Catatan</td>
                    <td>{{ $log->catatan ?: '-' }}</td>
                </tr>
            </table>

            @if($log->file_bukti)
                @php
                    $decoded = json_decode($log->file_bukti, true) ?: [];
                    $fileData = isset($decoded['file']) ? $decoded['file'] : null;
                    $gdriveData = isset($decoded['gdrive']) ? $decoded['gdrive'] : null;
                @endphp
                @if($fileData || $gdriveData)
                    <div style="margin-top: 10px;">
                        <strong>Bukti:</strong>
                        @if($fileData)
                            @php
                                $filePath = storage_path('app/public/' . $fileData);
                                $fileExt = pathinfo($fileData, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif']) && file_exists($filePath))
                                <div style="margin-top: 5px;">
                                    <img src="{{ $filePath }}" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                                </div>
                            @else
                                <p style="margin: 5px 0; font-size: 11px;">File: {{ basename($fileData) }}</p>
                            @endif
                        @endif
                        @if($gdriveData)
                            <p style="margin: 5px 0; font-size: 11px; color: #4e73df;">Link: {{ $gdriveData }}</p>
                        @endif
                    </div>
                @endif
            @endif
        </div>
        @if(!$loop->last)
            <hr style="border: 1px dashed #ddd; margin: 20px 0;">
        @endif
    @empty
        <p style="text-align: center; padding: 20px;">Tidak ada data</p>
    @endforelse

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem | Halaman {PAGE_NUM} dari {PAGE_COUNT}</p>
    </div>
</body>
</html>