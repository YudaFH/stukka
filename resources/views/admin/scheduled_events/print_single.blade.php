<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Kerja & Rincian Event - {{ $event->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.4;
            font-size: 11.5px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
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
        .btn-primary { background-color: #001D5E; color: #ffffff; }
        .btn-primary:hover { background-color: #001138; }
        .btn-outline { background-color: #ffffff; color: #475569; border: 1.5px solid #cbd5e1; }
        .btn-outline:hover { background-color: #f1f5f9; }
        .paper {
            max-width: 960px;
            margin: 24px auto;
            background: #ffffff;
            padding: 40px 48px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        }
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #001D5E;
            padding-bottom: 18px;
            margin-bottom: 20px;
        }
        .brand-title { font-size: 20px; font-weight: 900; color: #001D5E; }
        .brand-tagline { font-size: 11px; color: #64748b; font-weight: 600; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 17px; font-weight: 900; color: #001D5E; text-transform: uppercase; }
        .doc-title p { font-size: 11px; color: #F7941D; font-weight: 800; margin-top: 2px; }
        
        .section-title {
            font-size: 13px;
            font-weight: 900;
            color: #001D5E;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 24px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            padding: 16px;
            border-radius: 12px;
        }
        .info-row { display: flex; flex-direction: column; gap: 2px; }
        .info-label { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; }
        .info-value { font-size: 13px; font-weight: 800; color: #001D5E; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 11px; }
        th { background: #001D5E; color: #ffffff; font-weight: 800; text-align: left; padding: 8px 10px; }
        td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        tr:nth-child(even) td { background-color: #fcfcfd; }

        .signature-section { margin-top: 36px; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .sig-box { width: 200px; text-align: center; }
        .sig-title { font-size: 11px; color: #64748b; font-weight: 700; }
        .sig-space { height: 60px; }
        .sig-name { font-size: 12.5px; font-weight: 800; color: #001D5E; border-bottom: 1.5px solid #001D5E; padding-bottom: 2px; }
        .sig-role { font-size: 10.5px; color: #64748b; font-weight: 600; margin-top: 3px; }

        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; padding: 0 !important; }
            .paper { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
            @page { size: A4 portrait; margin: 12mm; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('admin.scheduled-events.show', $event->id) }}" class="btn btn-outline">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            <span>Kembali ke Detail Event</span>
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
            <span>Cetak / Simpan PDF</span>
        </button>
    </div>

    <div class="paper">
        <div class="kop-header">
            <div>
                <div class="brand-title">STUKKA DIGITAL CREATIVE</div>
                <div class="brand-tagline">Professional Multimedia, Event Production & Technology</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 3px;">
                    Jl. Stukka Creative No. 8 • Telp: (021) 8899-7700 • info@stukkadigital.com
                </div>
            </div>
            <div class="doc-title">
                <h1>Surat Perintah Kerja (SPK) & Rincian Event</h1>
                <p>EVENT ID: #EV-{{ str_pad($event->id, 4, '0', STR_PAD_LEFT) }}</p>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 3px;">
                    Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </div>
        </div>

        {{-- Profil Event --}}
        <div class="section-title">
            <i data-lucide="calendar" style="width: 16px; height: 16px; color: #F7941D;"></i>
            <span>Informasi Acara & Klien</span>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Judul Event</span>
                <span class="info-value">{{ $event->title }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kategori Acara</span>
                <span class="info-value">{{ $event->event_type ?? 'Event Produksi' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Klien / Penyelenggara</span>
                <span class="info-value">{{ $event->client_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kontak Klien</span>
                <span class="info-value">{{ $event->client_phone ?? '-' }} • {{ $event->client_email ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Acara</span>
                <span class="info-value">
                    {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d F Y') }}
                    @if($event->end_date && $event->end_date != $event->start_date)
                        s/d {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('d F Y') }}
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu On-Site</span>
                <span class="info-value">{{ $event->start_time ? substr($event->start_time, 0, 5) : '08:00' }} s/d {{ $event->end_time ? substr($event->end_time, 0, 5) : 'Selesai' }} WIB</span>
            </div>
            <div class="info-row" style="grid-column: span 2;">
                <span class="info-label">Lokasi / Venue</span>
                <span class="info-value">{{ $event->location ?? '-' }} {{ $event->venue_detail ? '('.$event->venue_detail.')' : '' }}</span>
            </div>
        </div>

        {{-- Ringkasan Keuangan --}}
        <div class="section-title">
            <i data-lucide="wallet" style="width: 16px; height: 16px; color: #16a34a;"></i>
            <span>Ringkasan Nilai Kontrak & Finansial</span>
        </div>

        <table style="margin-bottom: 16px;">
            <thead>
                <tr>
                    <th>Nilai Kontrak</th>
                    <th>Pembayaran Diterima</th>
                    <th>Sisa Piutang Klien</th>
                    <th>Total Biaya Produksi</th>
                    <th>Estimasi Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: 800; font-size: 12px;">
                    <td style="color: #001D5E;">Rp {{ number_format($event->contract_amount, 0, ',', '.') }}</td>
                    <td style="color: #16a34a;">Rp {{ number_format($event->total_paid, 0, ',', '.') }}</td>
                    <td style="color: #e11d48;">Rp {{ number_format($event->remaining_receivable, 0, ',', '.') }}</td>
                    <td style="color: #ea580c;">Rp {{ number_format($event->total_expense, 0, ',', '.') }}</td>
                    <td style="color: {{ $event->net_profit >= 0 ? '#16a34a' : '#dc2626' }};">
                        Rp {{ number_format($event->net_profit, 0, ',', '.') }} ({{ $event->profit_margin_percentage }}%)
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Kru Lapangan --}}
        <div class="section-title">
            <i data-lucide="users" style="width: 16px; height: 16px; color: #001D5E;"></i>
            <span>Kru Lapangan Bertugas ({{ $event->crewAssignments->count() }} Personel)</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px; text-align: center;">No</th>
                    <th>Nama Kru</th>
                    <th>Tipe Kru</th>
                    <th>Peran / Tugas</th>
                    <th>Kontak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($event->crewAssignments as $idx => $c)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                        <td style="font-weight: 800; color: #001D5E;">
                            {{ $c->crew_type === 'internal' ? ($c->user ? $c->user->name : 'Staff') : $c->external_name }}
                        </td>
                        <td>
                            <span style="font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: {{ $c->crew_type === 'internal' ? '#eff6ff' : '#fef3c7' }}; color: {{ $c->crew_type === 'internal' ? '#1d4ed8' : '#b45309' }};">
                                {{ $c->crew_type === 'internal' ? 'Internal' : 'Freelance' }}
                            </span>
                        </td>
                        <td style="font-weight: 700;">{{ $c->role_in_event }}</td>
                        <td style="color: #475569;">
                            {{ $c->crew_type === 'internal' ? ($c->user->phone ?? $c->user->email) : ($c->external_phone ?? '-') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 14px; color: #94a3b8; font-style: italic;">
                            Belum ada kru yang ditugaskan untuk event ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Rincian Pengeluaran Vendor / Produksi --}}
        @if($event->expenses->count() > 0)
            <div class="section-title">
                <i data-lucide="receipt" style="width: 16px; height: 16px; color: #ea580c;"></i>
                <span>Rincian Pengeluaran Vendor & Logistik</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px; text-align: center;">No</th>
                        <th>Kategori</th>
                        <th>Deskripsi Pengeluaran</th>
                        <th style="width: 120px; text-align: right;">Nominal</th>
                        <th style="width: 100px; text-align: center;">Status Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->expenses as $idx => $exp)
                        <tr>
                            <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                            <td style="font-weight: 700;">{{ $exp->category }}</td>
                            <td>{{ $exp->description }}</td>
                            <td style="text-align: right; font-weight: 700;">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            <td style="text-align: center; font-weight: 800; font-size: 10px; color: {{ $exp->status === 'paid' ? '#16a34a' : '#ea580c' }};">
                                {{ $exp->status === 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Tanda Tangan SPK --}}
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Pihak Klien / Pemesan,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $event->client_name }}</div>
                <div class="sig-role">Penyelenggara Acara</div>
            </div>

            <div class="sig-box">
                <div class="sig-title">Stukka Digital Creative,</div>
                <div class="sig-space"></div>
                <div class="sig-name">Pimpinan Produksi</div>
                <div class="sig-role">Head of Production</div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
