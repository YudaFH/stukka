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
    .time { text-align: center; mso-number-format: "hh\:mm"; }
    .date { text-align: center; mso-number-format: "yyyy-mm-dd"; }
    .total-row { background-color: #F1F5F9; font-weight: bold; }
</style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Email / Kontak</th>
                <th>Jam Masuk</th>
                <th>Lokasi Masuk</th>
                <th>Detail Lokasi Masuk</th>
                <th>Jam Pulang</th>
                <th>Lokasi Pulang</th>
                <th>Status Presensi</th>
                <th>Catatan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $idx => $att)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($att->date)->format('Y-m-d') }}</td>
                    <td>{{ $att->user ? $att->user->name : 'N/A' }}</td>
                    <td>{{ $att->user ? ($att->user->email ?? $att->user->phone) : '-' }}</td>
                    <td class="time">{{ $att->time_in ? substr($att->time_in, 0, 5) : '-' }}</td>
                    <td class="text-center">{{ ($att->location_status_in ?? 'office') === 'outside' ? 'Luar Kantor' : 'Di Kantor' }}</td>
                    <td>
                        @if($att->location_name_in)
                            {{ $att->location_name_in }}
                        @elseif($att->latitude_in)
                            {{ $att->latitude_in }}, {{ $att->longitude_in }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="time">{{ $att->time_out ? substr($att->time_out, 0, 5) : '-' }}</td>
                    <td class="text-center">
                        @if($att->time_out)
                            {{ ($att->location_status_out ?? 'office') === 'outside' ? 'Luar Kantor' : 'Di Kantor' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $att->status === 'on_time' ? 'Tepat Waktu' : ($att->status === 'late' ? 'Terlambat' : 'Lembur / Luar Jam') }}</td>
                    <td>{{ $att->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 12px; color: #64748b; font-style: italic;">
                        Tidak ada data presensi yang sesuai.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($attendances->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL PRESENSI:</td>
                <td colspan="2" class="text-center">{{ $attendances->count() }} Kehadiran</td>
                <td colspan="5">
                    Tepat Waktu: {{ $attendances->where('status', 'on_time')->count() }} • 
                    Terlambat: {{ $attendances->where('status', 'late')->count() }} • 
                    Luar Kantor: {{ $attendances->where('location_status_in', 'outside')->count() }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
