<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Event & Keuangan - Stukka Digital</title>
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
            font-size: 11px;
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
            max-width: 1280px;
            margin: 24px auto;
            background: #ffffff;
            padding: 36px 44px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }

        /* Kop */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #001D5E;
            padding-bottom: 18px;
            margin-bottom: 20px;
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
            margin-bottom: 20px;
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
            font-size: 15px;
            font-weight: 900;
            color: #001D5E;
            margin-top: 2px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 10.5px;
        }
        th {
            background-color: #001D5E;
            color: #ffffff;
            font-weight: 800;
            text-align: left;
            padding: 8px 8px;
            font-size: 10px;
            letter-spacing: 0.3px;
        }
        td {
            padding: 8px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        tr:nth-child(even) td {
            background-color: #fcfcfd;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2.5px 6px;
            border-radius: 5px;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-scheduled { background: #eff6ff; color: #1d4ed8; }
        .badge-ongoing { background: #fef3c7; color: #b45309; }
        .badge-completed { background: #dcfce7; color: #15803d; }
        .badge-cancelled { background: #fee2e2; color: #b91c1c; }

        /* Signature Section */
        .signature-section {
            margin-top: 32px;
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
            height: 65px;
        }
        .sig-name {
            font-size: 12.5px;
            font-weight: 800;
            color: #001D5E;
            border-bottom: 1.5px solid #001D5E;
            padding-bottom: 2px;
        }
        .sig-role {
            font-size: 10.5px;
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
                size: A4 landscape;
                margin: 10mm;
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
            <a href="{{ route('admin.scheduled-events.index') }}" class="btn btn-outline">
                <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
                <span>Kembali ke Jadwal Event</span>
            </a>
            <span style="font-size: 13px; font-weight: 700; color: #64748b;">
                Laporan Event & Keuangan: <strong style="color: #001D5E;">{{ $periodLabel }}</strong>
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('admin.scheduled-events.export.excel', request()->all()) }}" class="btn btn-outline" style="color: #15803d; border-color: #86efac;">
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
                <div style="font-size: 10px; color: #64748b; margin-top: 3px;">
                    Jl. Stukka Creative No. 8 • Telp: (021) 8899-7700 • info@stukkadigital.com
                </div>
            </div>
            <div class="doc-title">
                <h1>Laporan Operasional & Keuangan Event</h1>
                <p>PERIODE: {{ strtoupper($periodLabel) }}</p>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 3px;">
                    Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        {{-- KPI Summary Grid --}}
        <div class="meta-grid">
            <div class="meta-card">
                <div class="meta-label">Total Omset Kontrak</div>
                <div class="meta-val">Rp {{ number_format($totalContracts, 0, ',', '.') }}</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Pemasukan Diterima (Cash In)</div>
                <div class="meta-val" style="color: #16a34a;">Rp {{ number_format($totalReceived, 0, ',', '.') }}</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Total Biaya Produksi</div>
                <div class="meta-val" style="color: #ea580c;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Total Laba Bersih Proyek</div>
                <div class="meta-val" style="color: {{ $totalNetProfit >= 0 ? '#16a34a' : '#dc2626' }};">
                    Rp {{ number_format($totalNetProfit, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 28px; text-align: center;">No</th>
                    <th>Judul Event & Klien</th>
                    <th style="width: 80px;">Tanggal</th>
                    <th style="width: 100px;">Lokasi</th>
                    <th style="width: 80px; text-align: center;">Status</th>
                    <th style="width: 95px; text-align: right;">Nilai Kontrak</th>
                    <th style="width: 95px; text-align: right;">Diterima</th>
                    <th style="width: 95px; text-align: right;">Piutang</th>
                    <th style="width: 95px; text-align: right;">Biaya Produksi</th>
                    <th style="width: 95px; text-align: right;">Laba Bersih</th>
                    <th style="width: 60px; text-align: center;">Margin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $idx => $ev)
                    <tr>
                        <td style="text-align: center; color: #64748b; font-weight: 700;">{{ $idx + 1 }}</td>
                        <td>
                            <div style="font-weight: 800; color: #001D5E;">{{ $ev->title }}</div>
                            <div style="font-size: 9.5px; color: #64748b;">
                                Klien: <strong>{{ $ev->client_name }}</strong> • {{ $ev->event_type ?? 'Event' }}
                            </div>
                        </td>
                        <td style="font-weight: 700;">
                            {{ \Carbon\Carbon::parse($ev->start_date)->translatedFormat('d M Y') }}
                        </td>
                        <td style="color: #475569;">
                            {{ Str::limit($ev->location ?? '-', 24) }}
                        </td>
                        <td style="text-align: center;">
                            <span class="badge 
                                @if($ev->status === 'scheduled') badge-scheduled
                                @elseif($ev->status === 'ongoing') badge-ongoing
                                @elseif($ev->status === 'completed') badge-completed
                                @else badge-cancelled @endif">
                                {{ $ev->status === 'scheduled' ? 'Terjadwal' : ($ev->status === 'ongoing' ? 'Berlangsung' : ($ev->status === 'completed' ? 'Selesai' : 'Batal')) }}
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 700;">
                            Rp {{ number_format($ev->contract_amount, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #16a34a;">
                            Rp {{ number_format($ev->total_paid, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #e11d48;">
                            Rp {{ number_format($ev->remaining_receivable, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #ea580c;">
                            Rp {{ number_format($ev->total_expense, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 800; color: {{ $ev->net_profit >= 0 ? '#16a34a' : '#dc2626' }};">
                            Rp {{ number_format($ev->net_profit, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center; font-weight: 800;">
                            {{ $ev->profit_margin_percentage }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 32px; color: #94a3b8; font-style: italic;">
                            Tidak ada data event operasional yang tercatat pada filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($events->count() > 0)
            <tfoot>
                <tr style="background-color: #001D5E; color: #ffffff; font-weight: 800;">
                    <td colspan="5" style="text-align: right; padding: 8px 10px; color: #ffffff;">
                        TOTAL KESELURUHAN ({{ $events->count() }} Event):
                    </td>
                    <td style="text-align: right; color: #ffffff;">Rp {{ number_format($totalContracts, 0, ',', '.') }}</td>
                    <td style="text-align: right; color: #4ade80;">Rp {{ number_format($totalReceived, 0, ',', '.') }}</td>
                    <td style="text-align: right; color: #fda4af;">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</td>
                    <td style="text-align: right; color: #fed7aa;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
                    <td style="text-align: right; color: #38bdf8;">Rp {{ number_format($totalNetProfit, 0, ',', '.') }}</td>
                    <td style="text-align: center; color: #ffffff;">-</td>
                </tr>
            </tfoot>
            @endif
        </table>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Dibuat Oleh (Finance & Produksi),</div>
                <div class="sig-space"></div>
                <div class="sig-name">Admin Event & Finansial</div>
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
