<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi Karyawan - Stukka Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.4;
            font-size: 12px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Top Action Bar for screen only */
        .no-print {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }
        .btn-primary {
            background-color: #001D5E;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #001138;
        }
        .btn-orange {
            background-color: #F7941D;
            color: #ffffff;
        }
        .btn-orange:hover {
            background-color: #d67b0f;
        }
        .btn-outline {
            background-color: #ffffff;
            color: #475569;
            border: 1.5px solid #cbd5e1;
        }
        .btn-outline:hover {
            background-color: #f1f5f9;
        }

        /* Printable Paper Container */
        .paper {
            max-width: 1080px;
            margin: 24px auto;
            background: #ffffff;
            padding: 36px 44px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }

        /* Header / Kop */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #001D5E;
            padding-bottom: 18px;
            margin-bottom: 22px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: 900;
            color: #001D5E;
            letter-spacing: -0.5px;
        }
        .brand-tagline {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            margin-top: 2px;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h1 {
            font-size: 17px;
            font-weight: 900;
            color: #001D5E;
            text-transform: uppercase;
        }
        .doc-title p {
            font-size: 11px;
            color: #F7941D;
            font-weight: 800;
            margin-top: 2px;
        }

        /* Meta Info Grid */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }
        .meta-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            border-radius: 10px;
        }
        .meta-label {
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-val {
            font-size: 13px;
            font-weight: 800;
            color: #001D5E;
            margin-top: 2px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 11.5px;
        }
        th {
            background-color: #001D5E;
            color: #ffffff;
            font-weight: 800;
            text-align: left;
            padding: 10px 10px;
            font-size: 11px;
            letter-spacing: 0.3px;
        }
        th:first-child { border-top-left-radius: 8px; }
        th:last-child { border-top-right-radius: 8px; }
        td {
            padding: 9px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        tr:nth-child(even) td {
            background-color: #fcfcfd;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 7px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-ontime { background: #dcfce7; color: #15803d; }
        .badge-late { background: #fef3c7; color: #b45309; }
        .badge-overtime { background: #e0e7ff; color: #4338ca; }
        .badge-office { background: #f1f5f9; color: #475569; }
        .badge-outside { background: #fee2e2; color: #b91c1c; }

        /* Signature Section */
        .signature-section {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-box {
            width: 220px;
            text-align: center;
        }
        .sig-title {
            font-size: 11px;
            color: #64748b;
            font-weight: 700;
        }
        .sig-space {
            height: 70px;
        }
        .sig-name {
            font-size: 13px;
            font-weight: 800;
            color: #001D5E;
            border-bottom: 1.5px solid #001D5E;
            padding-bottom: 2px;
        }
        .sig-role {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            margin-top: 3px;
        }

        /* Print Media Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .paper {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>

    {{-- Screen Action Bar --}}
    <div class="no-print">
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('admin.attendances.index') }}" class="btn btn-outline">
                <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
                <span>Kembali ke Rekap Absensi</span>
            </a>
            <span style="font-size: 13px; font-weight: 700; color: #64748b;">
                Laporan Rekapitulasi Presensi: <strong style="color: #001D5E;">{{ $periodLabel }}</strong>
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('admin.attendances.export.excel', request()->all()) }}" class="btn btn-outline" style="color: #15803d; border-color: #86efac;">
                <i data-lucide="file-spreadsheet" style="width: 16px; height: 16px; color: #16a34a;"></i>
                <span>Unduh Format Excel (.xls)</span>
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    {{-- Printable Paper Sheet --}}
    <div class="paper">

        {{-- Kop Resmi --}}
        <div class="kop-header">
            <div>
                <div class="brand-title">STUKKA DIGITAL CREATIVE</div>
                <div class="brand-tagline">Professional Multimedia, Event Production & Creative Technology</div>
                <div style="font-size: 10.5px; color: #64748b; margin-top: 3px;">
                    Jl. Stukka Creative No. 8 • Telp: (021) 8899-7700 • info@stukkadigital.com
                </div>
            </div>
            <div class="doc-title">
                <h1>Laporan Presensi Karyawan</h1>
                <p>PERIODE: {{ strtoupper($periodLabel) }}</p>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 3px;">
                    Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        {{-- KPI Summary Grid --}}
        <div class="meta-grid">
            <div class="meta-card">
                <div class="meta-label">Total Presensi</div>
                <div class="meta-val">{{ $totalHadir }} <span style="font-size: 11px; font-weight: 600; color: #64748b;">Record</span></div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Tepat Waktu</div>
                <div class="meta-val" style="color: #16a34a;">{{ $totalOnTime }} <span style="font-size: 11px; font-weight: 600; color: #64748b;">Karyawan</span></div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Terlambat</div>
                <div class="meta-val" style="color: #d97706;">{{ $totalLate }} <span style="font-size: 11px; font-weight: 600; color: #64748b;">Karyawan</span></div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Di Luar Kantor</div>
                <div class="meta-val" style="color: #dc2626;">{{ $totalOutside }} <span style="font-size: 11px; font-weight: 600; color: #64748b;">Presensi</span></div>
            </div>
        </div>

        @if($filterUser)
            <div style="margin-bottom: 16px; padding: 10px 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 11.5px; color: #1e40af;">
                Menampilkan data spesifik karyawan: <strong>{{ $filterUser->name }}</strong> ({{ $filterUser->email ?? $filterUser->phone }})
            </div>
        @endif

        {{-- Data Table --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 32px; text-align: center;">No</th>
                    <th style="width: 85px;">Tanggal</th>
                    <th>Nama Karyawan</th>
                    <th style="width: 75px; text-align: center;">Masuk</th>
                    <th style="width: 140px;">Lokasi Masuk</th>
                    <th style="width: 75px; text-align: center;">Pulang</th>
                    <th style="width: 140px;">Lokasi Pulang</th>
                    <th style="width: 95px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $idx => $att)
                    <tr>
                        <td style="text-align: center; color: #64748b; font-weight: 700;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 700;">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}</td>
                        <td>
                            <div style="font-weight: 800; color: #001D5E;">{{ $att->user ? $att->user->name : 'N/A' }}</div>
                            <div style="font-size: 10px; color: #64748b;">{{ $att->user ? ($att->user->email ?? $att->user->phone) : '-' }}</div>
                        </td>
                        <td style="text-align: center; font-weight: 800; color: #001D5E;">
                            {{ $att->time_in ? substr($att->time_in, 0, 5) : '-' }}
                        </td>
                        <td>
                            <span class="badge {{ ($att->location_status_in ?? 'office') === 'outside' ? 'badge-outside' : 'badge-office' }}">
                                {{ ($att->location_status_in ?? 'office') === 'outside' ? 'Luar Kantor' : 'Di Kantor' }}
                            </span>
                            @if($att->location_name_in)
                                <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">{{ Str::limit($att->location_name_in, 32) }}</div>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 800; color: #001D5E;">
                            {{ $att->time_out ? substr($att->time_out, 0, 5) : '-' }}
                        </td>
                        <td>
                            @if($att->time_out)
                                <span class="badge {{ ($att->location_status_out ?? 'office') === 'outside' ? 'badge-outside' : 'badge-office' }}">
                                    {{ ($att->location_status_out ?? 'office') === 'outside' ? 'Luar Kantor' : 'Di Kantor' }}
                                </span>
                                @if($att->location_name_out)
                                    <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">{{ Str::limit($att->location_name_out, 32) }}</div>
                                @endif
                            @else
                                <span style="color: #94a3b8;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="badge 
                                @if($att->status === 'on_time') badge-ontime
                                @elseif($att->status === 'late') badge-late
                                @else badge-overtime @endif">
                                {{ $att->status === 'on_time' ? 'Tepat Waktu' : ($att->status === 'late' ? 'Terlambat' : 'Lembur') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px; color: #94a3b8; font-style: italic;">
                            Tidak ada data kehadiran yang tercatat pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Diverifikasi Oleh,</div>
                <div class="sig-space"></div>
                <div class="sig-name">Admin Personalia & HR</div>
                <div class="sig-role">Stukka Digital Creative</div>
            </div>

            <div class="sig-box">
                <div class="sig-title">Mengetahui & Menyetujui,</div>
                <div class="sig-space"></div>
                <div class="sig-name">Pimpinan Operasional</div>
                <div class="sig-role">Direktur Stukka Creative</div>
            </div>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
