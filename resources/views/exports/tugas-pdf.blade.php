<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #3B28CC;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #3B28CC;
            margin: 0;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 11px;
            color: #666666;
            margin: 5px 0 0 0;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 10px;
            color: #555555;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #3B28CC;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 10px;
            border: 1px solid #3B28CC;
        }
        .data-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 4px;
            text-align: center;
        }
        .badge-tinggi {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-sedang {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-rendah {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-menunggu {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-revisi {
            background-color: #ffe4e6;
            color: #9f1239;
        }
        .status-belum {
            background-color: #f1f5f9;
            color: #475569;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999999;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">Laporan Monitoring Tugas</h1>
        <p class="subtitle">Sistem Management Tugas Kantor - Handman</p>
    </div>

    <div class="meta-info">
        <strong>Departemen:</strong> {{ $departemenName }} <br>
        <strong>Kategori Filter:</strong> {{ $kategoriFilter }} <br>
        <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i') }}
    </div>

    <table style="width: 100%; border-collapse: separate; border-spacing: 8px 0px; margin-bottom: 25px; border: none; background: transparent;">
        <tr>
            <td style="width: 20%; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 8px; text-align: center; vertical-align: middle;">
                <div style="font-size: 7px; color: #64748b; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; white-space: nowrap;">Total Tugas</div>
                <div style="font-size: 16px; font-weight: bold; color: #1e293b; line-height: 1;">{{ $totalTugas }}</div>
            </td>
            <td style="width: 20%; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px 8px; text-align: center; vertical-align: middle;">
                <div style="font-size: 7px; color: #166534; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; white-space: nowrap;">Selesai</div>
                <div style="font-size: 16px; font-weight: bold; color: #15803d; line-height: 1;">{{ $tugasSelesai }}</div>
            </td>
            <td style="width: 20%; background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 8px; text-align: center; vertical-align: middle;">
                <div style="font-size: 7px; color: #92400e; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; white-space: nowrap;">Berjalan</div>
                <div style="font-size: 16px; font-weight: bold; color: #d97706; line-height: 1;">{{ $tugasBerjalan }}</div>
            </td>
            <td style="width: 20%; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 10px 8px; text-align: center; vertical-align: middle;">
                <div style="font-size: 7px; color: #1e40af; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; white-space: nowrap;">Menunggu Review</div>
                <div style="font-size: 16px; font-weight: bold; color: #1d4ed8; line-height: 1;">{{ $tugasMenunggu }}</div>
            </td>
            <td style="width: 20%; background-color: #faf5ff; border: 1px solid #e9d5ff; border-radius: 6px; padding: 10px 8px; text-align: center; vertical-align: middle;">
                <div style="font-size: 7px; color: #6b21a8; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; white-space: nowrap;">Kinerja Staff</div>
                <div style="font-size: 16px; font-weight: bold; color: #7e22ce; line-height: 1; margin-bottom: 4px;">{{ $currentEfficiency }}%</div>
                <div style="font-size: 7px; font-weight: bold; white-space: nowrap; line-height: 1;">
                    @if($change > 0)
                        <span style="color: #16a34a;">▲ +{{ $change }}% Naik</span>
                    @elseif($change < 0)
                        <span style="color: #dc2626;">▼ {{ $change }}% Turun</span>
                    @else
                        <span style="color: #4b5563;">● 0% Stabil</span>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Tugas</th>
                <th style="width: 15%;">Departemen</th>
                <th style="width: 15%;">Penerima</th>
                <th style="width: 10%;">Prioritas</th>
                <th style="width: 15%;">Tenggat Waktu</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tugasList as $index => $t)
                @php
                    $penerima = '-';
                    if ($t->kategoritugas === 'Individu' && $t->detailTugas && $t->detailTugas->user) {
                        $penerima = $t->detailTugas->user->nama_lengkap;
                    } elseif ($t->kategoritugas === 'Kelompok' && $t->detailTugas && $t->detailTugas->grupKerja) {
                        $penerima = $t->detailTugas->grupKerja->nama_grup;
                    }
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $t->nama_tugas }}</strong>
                        <div style="font-size: 8px; color: #666666; margin-top: 3px;">Kategori: {{ $t->kategoritugas === 'Kelompok' ? 'Departemen' : $t->kategoritugas }}</div>
                    </td>
                    <td>{{ $t->departemen->nama_departemen ?? '-' }}</td>
                    <td>{{ $penerima }}</td>
                    <td>
                        @if($t->prioritas === 'Tinggi')
                            <span class="badge badge-tinggi">Tinggi</span>
                        @elseif($t->prioritas === 'Sedang')
                            <span class="badge badge-sedang">Sedang</span>
                        @else
                            <span class="badge badge-rendah">Rendah</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($t->deadline_tugas)->format('d M Y, H:i') }}</td>
                    <td>
                        @if($t->status_tugas === 'Selesai')
                            <span class="badge status-selesai">Selesai</span>
                        @elseif($t->status_tugas === 'Menunggu Persetujuan')
                            <span class="badge status-menunggu">Menunggu Review</span>
                        @elseif($t->status_tugas === 'Revisi')
                            <span class="badge status-revisi">Revisi</span>
                        @else
                            <span class="badge status-belum">Belum Selesai</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #999999; font-style: italic;">Tidak ada data tugas yang sesuai dengan kriteria filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Handman pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>
