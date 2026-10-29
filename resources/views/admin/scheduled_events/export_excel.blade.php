<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    body { font-family: 'Segoe UI', Calibri, Arial, sans-serif; font-size: 11pt; color: #1e293b; }
    .title { font-size: 16pt; font-weight: bold; color: #001D5E; }
    .subtitle { font-size: 11pt; color: #64748b; }
    .meta-label { font-weight: bold; color: #001D5E; width: 150px; }
    .th-header { background-color: #001D5E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #001138; vertical-align: middle; height: 32px; }
    .th-sub { background-color: #F7941D; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #D67B0F; }
    .td-data { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: middle; }
    .td-center { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; vertical-align: middle; }
    .td-currency { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: right; vertical-align: middle; mso-number-format: "\Rp\ #\,\#\#0"; }
    .td-percent { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: right; vertical-align: middle; mso-number-format: "0\.0%"; }
    .td-date { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; vertical-align: middle; mso-number-format: "yyyy-mm-dd"; }
    .badge-scheduled { background-color: #eff6ff; color: #1d4ed8; font-weight: bold; text-align: center; }
    .badge-ongoing { background-color: #fef3c7; color: #b45309; font-weight: bold; text-align: center; }
    .badge-completed { background-color: #dcfce7; color: #15803d; font-weight: bold; text-align: center; }
    .badge-cancelled { background-color: #fee2e2; color: #b91c1c; font-weight: bold; text-align: center; }
    .profit-positive { color: #15803d; font-weight: bold; }
    .profit-negative { color: #b91c1c; font-weight: bold; }
</style>
</head>
<body>

    <table>
        <tr>
            <td colspan="18" class="title">LAPORAN OPERASIONAL & KEUANGAN EVENT</td>
        </tr>
        <tr>
            <td colspan="18" class="subtitle">STUKKA DIGITAL CREATIVE — Sistem Informasi Manajemen Jadwal, Kru & Keuangan Event</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td class="meta-label">Periode Data:</td>
            <td colspan="17" style="font-weight: bold; color: #001D5E;">{{ $periodLabel }}</td>
        </tr>
        @if($statusFilter)
        <tr>
            <td class="meta-label">Status Filter:</td>
            <td colspan="17" style="font-weight: bold; text-transform: uppercase;">{{ $statusFilter }}</td>
        </tr>
        @endif
        <tr>
            <td class="meta-label">Waktu Export:</td>
            <td colspan="17">{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Total Proyek:</td>
            <td colspan="17" style="font-weight: bold;">{{ $events->count() }} Event Tercatat</td>
        </tr>
        <tr><td></td></tr>
    </table>

    {{-- TABEL DATA EVENT & KEUANGAN --}}
    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th class="th-header" style="width: 40px;">No</th>
                <th class="th-header" style="width: 220px;">Judul Event</th>
                <th class="th-header" style="width: 120px;">Kategori</th>
                <th class="th-header" style="width: 160px;">Nama Klien</th>
                <th class="th-header" style="width: 130px;">Kontak Klien</th>
                <th class="th-header" style="width: 100px;">Tgl Mulai</th>
                <th class="th-header" style="width: 100px;">Tgl Selesai</th>
                <th class="th-header" style="width: 180px;">Lokasi / Venue</th>
                <th class="th-header" style="width: 110px;">Status Event</th>
                <th class="th-header" style="width: 130px;">Nilai Kontrak (Rp)</th>
                <th class="th-header" style="width: 130px;">Diterima Klien (Rp)</th>
                <th class="th-header" style="width: 130px;">Piutang Klien (Rp)</th>
                <th class="th-header" style="width: 130px;">Biaya Produksi (Rp)</th>
                <th class="th-header" style="width: 130px;">Biaya Terbayar (Rp)</th>
                <th class="th-header" style="width: 130px;">Hutang Vendor (Rp)</th>
                <th class="th-header" style="width: 130px;">Laba Bersih (Rp)</th>
                <th class="th-header" style="width: 80px;">Margin (%)</th>
                <th class="th-header" style="width: 260px;">Alokasi Kru Lapangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $idx => $ev)
                @php
                    $crewList = $ev->crewAssignments->map(function($c) {
                        $name = $c->crew_type === 'internal' ? ($c->user ? $c->user->name : 'Staff') : $c->external_name;
                        return $name . ' (' . $c->role_in_event . ')';
                    })->implode(', ');
                @endphp
                <tr>
                    <td class="td-center">{{ $idx + 1 }}</td>
                    <td class="td-data" style="font-weight: bold; color: #001D5E;">{{ $ev->title }}</td>
                    <td class="td-data">{{ $ev->event_type ?? '-' }}</td>
                    <td class="td-data" style="font-weight: bold;">{{ $ev->client_name }}</td>
                    <td class="td-data">{{ $ev->client_phone ?? ($ev->client_email ?? '-') }}</td>
                    <td class="td-date">{{ \Carbon\Carbon::parse($ev->start_date)->format('Y-m-d') }}</td>
                    <td class="td-date">{{ $ev->end_date ? \Carbon\Carbon::parse($ev->end_date)->format('Y-m-d') : \Carbon\Carbon::parse($ev->start_date)->format('Y-m-d') }}</td>
                    <td class="td-data">{{ $ev->location ?? '-' }}</td>
                    <td class="td-center 
                        @if($ev->status === 'scheduled') badge-scheduled
                        @elseif($ev->status === 'ongoing') badge-ongoing
                        @elseif($ev->status === 'completed') badge-completed
                        @else badge-cancelled @endif">
                        {{ $ev->status === 'scheduled' ? 'Terjadwal' : ($ev->status === 'ongoing' ? 'Berlangsung' : ($ev->status === 'completed' ? 'Selesai' : 'Batal')) }}
                    </td>
                    <td class="td-currency">{{ $ev->contract_amount }}</td>
                    <td class="td-currency">{{ $ev->total_paid }}</td>
                    <td class="td-currency">{{ $ev->remaining_receivable }}</td>
                    <td class="td-currency">{{ $ev->total_expense }}</td>
                    <td class="td-currency">{{ $ev->total_paid_expense }}</td>
                    <td class="td-currency">{{ $ev->remaining_expense }}</td>
                    <td class="td-currency {{ $ev->net_profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                        {{ $ev->net_profit }}
                    </td>
                    <td class="td-center" style="font-weight: bold;">{{ $ev->profit_margin_percentage }}%</td>
                    <td class="td-data">{{ $crewList ?: 'Belum ada kru ditugaskan' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="td-center" style="padding: 20px; color: #64748b; font-style: italic;">
                        Tidak ada data event yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($events->count() > 0)
        <tfoot>
            <tr style="background-color: #001D5E; color: #ffffff; font-weight: bold;">
                <td colspan="9" style="text-align: right; padding: 10px; border: 1px solid #001138;">
                    TOTAL KESELURUHAN ({{ $events->count() }} Event):
                </td>
                <td class="td-currency" style="color: #ffffff; border: 1px solid #001138;">{{ $events->sum('contract_amount') }}</td>
                <td class="td-currency" style="color: #4ade80; border: 1px solid #001138;">{{ $events->sum('total_paid') }}</td>
                <td class="td-currency" style="color: #fb7185; border: 1px solid #001138;">{{ $events->sum('remaining_receivable') }}</td>
                <td class="td-currency" style="color: #ffffff; border: 1px solid #001138;">{{ $events->sum('total_expense') }}</td>
                <td class="td-currency" style="color: #fbbf24; border: 1px solid #001138;">{{ $events->sum('total_paid_expense') }}</td>
                <td class="td-currency" style="color: #fca5a5; border: 1px solid #001138;">{{ $events->sum('remaining_expense') }}</td>
                <td class="td-currency" style="color: #38bdf8; border: 1px solid #001138;">{{ $events->sum('net_profit') }}</td>
                <td class="td-center" style="color: #ffffff; border: 1px solid #001138;">-</td>
                <td style="border: 1px solid #001138;"></td>
            </tr>
        </tfoot>
        @endif
    </table>

</body>
</html>
