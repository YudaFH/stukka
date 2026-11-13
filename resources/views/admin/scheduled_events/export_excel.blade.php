<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    table { border-collapse: collapse; width: 100%; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
    th { background-color: #E2E8F0; color: #0F172A; font-weight: bold; text-align: center; border: 1px solid #94A3B8; padding: 6px 10px; vertical-align: middle; }
    td { border: 1px solid #CBD5E1; padding: 5px 8px; vertical-align: middle; color: #1E293B; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .currency { text-align: right; mso-number-format: "\Rp\ #\,\#\#0"; }
    .percent { text-align: right; mso-number-format: "0\.0%"; }
    .date { text-align: center; mso-number-format: "yyyy-mm-dd"; }
    .total-row { background-color: #F1F5F9; font-weight: bold; }
</style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Event</th>
                <th>Kategori</th>
                <th>Nama Klien</th>
                <th>Kontak Klien</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Lokasi / Venue</th>
                <th>Status</th>
                <th>Nilai Kontrak</th>
                <th>Diterima Klien</th>
                <th>Piutang Klien</th>
                <th>Biaya Produksi</th>
                <th>Biaya Terbayar</th>
                <th>Hutang Vendor</th>
                <th>Laba Bersih</th>
                <th>Margin (%)</th>
                <th>Alokasi Kru Lapangan</th>
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
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $ev->title }}</td>
                    <td>{{ $ev->event_type ?? '-' }}</td>
                    <td>{{ $ev->client_name }}</td>
                    <td>{{ $ev->client_phone ?? ($ev->client_email ?? '-') }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($ev->start_date)->format('Y-m-d') }}</td>
                    <td class="date">{{ $ev->end_date ? \Carbon\Carbon::parse($ev->end_date)->format('Y-m-d') : \Carbon\Carbon::parse($ev->start_date)->format('Y-m-d') }}</td>
                    <td>{{ $ev->location ?? '-' }}</td>
                    <td class="text-center">{{ $ev->status === 'scheduled' ? 'Terjadwal' : ($ev->status === 'ongoing' ? 'Berlangsung' : ($ev->status === 'completed' ? 'Selesai' : 'Batal')) }}</td>
                    <td class="currency">{{ $ev->contract_amount }}</td>
                    <td class="currency">{{ $ev->total_paid }}</td>
                    <td class="currency">{{ $ev->remaining_receivable }}</td>
                    <td class="currency">{{ $ev->total_expense }}</td>
                    <td class="currency">{{ $ev->total_paid_expense }}</td>
                    <td class="currency">{{ $ev->remaining_expense }}</td>
                    <td class="currency">{{ $ev->net_profit }}</td>
                    <td class="text-center">{{ $ev->profit_margin_percentage }}%</td>
                    <td>{{ $crewList ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="text-center" style="padding: 12px; color: #64748b; font-style: italic;">
                        Tidak ada data event yang sesuai.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($events->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="9" class="text-right">TOTAL:</td>
                <td class="currency">{{ $events->sum('contract_amount') }}</td>
                <td class="currency">{{ $events->sum('total_paid') }}</td>
                <td class="currency">{{ $events->sum('remaining_receivable') }}</td>
                <td class="currency">{{ $events->sum('total_expense') }}</td>
                <td class="currency">{{ $events->sum('total_paid_expense') }}</td>
                <td class="currency">{{ $events->sum('remaining_expense') }}</td>
                <td class="currency">{{ $events->sum('net_profit') }}</td>
                <td class="text-center">-</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
