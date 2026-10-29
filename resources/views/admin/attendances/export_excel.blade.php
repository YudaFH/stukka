<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    body { font-family: 'Segoe UI', Calibri, Arial, sans-serif; font-size: 11pt; color: #1e293b; }
    .title { font-size: 16pt; font-weight: bold; color: #001D5E; }
    .subtitle { font-size: 11pt; color: #64748b; }
    .meta-label { font-weight: bold; color: #001D5E; width: 140px; }
    .th-header { background-color: #001D5E; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #001138; vertical-align: middle; height: 32px; }
    .th-sub { background-color: #F7941D; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #D67B0F; }
    .td-data { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: middle; }
    .td-center { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; vertical-align: middle; }
    .td-number { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: right; vertical-align: middle; mso-number-format: "\#\,\#\#0"; }
    .td-time { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; vertical-align: middle; mso-number-format: "hh\:mm"; }
    .td-date { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; vertical-align: middle; mso-number-format: "yyyy-mm-dd"; }
    .badge-ontime { background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center; border: 1px solid #bbf7d0; }
    .badge-late { background-color: #fef3c7; color: #92400e; font-weight: bold; text-align: center; border: 1px solid #fde68a; }
    .badge-overtime { background-color: #e0e7ff; color: #3730a3; font-weight: bold; text-align: center; border: 1px solid #c7d2fe; }
    .badge-office { background-color: #f1f5f9; color: #334155; text-align: center; }
    .badge-outside { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
    .kpi-box { border: 1px solid #cbd5e1; background-color: #f8fafc; font-weight: bold; padding: 8px; }
</style>
</head>
<body>

    <table>
        <tr>
            <td colspan="11" class="title">LAPORAN REKAPITULASI PRESENSI KARYAWAN</td>
        </tr>
        <tr>
            <td colspan="11" class="subtitle">STUKKA DIGITAL CREATIVE — Sistem Informasi Manajemen Operasional & Kru</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td class="meta-label">Periode Laporan:</td>
            <td colspan="10" style="font-weight: bold; color: #001D5E;">{{ $periodLabel }}</td>
        </tr>
        @if($filterUser)
        <tr>
            <td class="meta-label">Filter Karyawan:</td>
            <td colspan="10" style="font-weight: bold;">{{ $filterUser->name }} ({{ $filterUser->email }})</td>
        </tr>
        @endif
        <tr>
            <td class="meta-label">Waktu Export:</td>
            <td colspan="10">{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Total Kehadiran:</td>
            <td colspan="10" style="font-weight: bold;">{{ $attendances->count() }} Presensi Tercatat</td>
        </tr>
        <tr><td></td></tr>
    </table>

    {{-- TABEL DATA PRESENSI --}}
    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th class="th-header" style="width: 40px;">No</th>
                <th class="th-header" style="width: 100px;">Tanggal</th>
                <th class="th-header" style="width: 180px;">Nama Karyawan</th>
                <th class="th-header" style="width: 140px;">Email / Kontak</th>
                <th class="th-header" style="width: 90px;">Jam Masuk</th>
                <th class="th-header" style="width: 130px;">Lokasi Masuk</th>
                <th class="th-header" style="width: 160px;">Detail Lokasi Masuk</th>
                <th class="th-header" style="width: 90px;">Jam Pulang</th>
                <th class="th-header" style="width: 130px;">Lokasi Pulang</th>
                <th class="th-header" style="width: 110px;">Status Presensi</th>
                <th class="th-header" style="width: 200px;">Catatan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $idx => $att)
                <tr>
                    <td class="td-center">{{ $idx + 1 }}</td>
                    <td class="td-date">{{ \Carbon\Carbon::parse($att->date)->format('Y-m-d') }}</td>
                    <td class="td-data" style="font-weight: bold; color: #001D5E;">{{ $att->user ? $att->user->name : 'N/A' }}</td>
                    <td class="td-data">{{ $att->user ? ($att->user->email ?? $att->user->phone) : '-' }}</td>
                    <td class="td-time">{{ $att->time_in ? substr($att->time_in, 0, 5) : '-' }}</td>
                    <td class="td-center {{ ($att->location_status_in ?? 'office') === 'outside' ? 'badge-outside' : 'badge-office' }}">
                        {{ ($att->location_status_in ?? 'office') === 'outside' ? '📍 Luar Kantor' : '🏢 Di Kantor' }}
                    </td>
                    <td class="td-data">
                        @if($att->location_name_in)
                            {{ $att->location_name_in }}
                        @elseif($att->latitude_in)
                            {{ $att->latitude_in }}, {{ $att->longitude_in }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="td-time">{{ $att->time_out ? substr($att->time_out, 0, 5) : '-' }}</td>
                    <td class="td-center {{ ($att->location_status_out ?? 'office') === 'outside' ? 'badge-outside' : 'badge-office' }}">
                        @if($att->time_out)
                            {{ ($att->location_status_out ?? 'office') === 'outside' ? '📍 Luar Kantor' : '🏢 Di Kantor' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="td-center 
                        @if($att->status === 'on_time') badge-ontime
                        @elseif($att->status === 'late') badge-late
                        @else badge-overtime @endif">
                        {{ $att->status === 'on_time' ? 'Tepat Waktu' : ($att->status === 'late' ? 'Terlambat' : 'Lembur / Luar Jam') }}
                    </td>
                    <td class="td-data">{{ $att->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="td-center" style="padding: 20px; color: #64748b; font-style: italic;">
                        Tidak ada data presensi yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($attendances->count() > 0)
        <tfoot>
            <tr>
                <td colspan="4" style="background-color: #f1f5f9; font-weight: bold; text-align: right; border: 1px solid #cbd5e1; padding: 8px;">
                    TOTAL KEHADIRAN:
                </td>
                <td colspan="2" style="background-color: #f1f5f9; font-weight: bold; text-align: center; border: 1px solid #cbd5e1;">
                    {{ $attendances->count() }} Presensi
                </td>
                <td colspan="5" style="background-color: #f1f5f9; font-size: 10pt; color: #475569; border: 1px solid #cbd5e1; padding: 8px;">
                    Tepat Waktu: <strong>{{ $attendances->where('status', 'on_time')->count() }}</strong> • 
                    Terlambat: <strong>{{ $attendances->where('status', 'late')->count() }}</strong> • 
                    Presensi Luar Kantor: <strong>{{ $attendances->where('location_status_in', 'outside')->count() }}</strong>
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

</body>
</html>
