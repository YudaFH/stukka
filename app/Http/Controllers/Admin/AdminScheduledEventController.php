<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EventCrew;
use App\Models\EventExpense;
use App\Models\ScheduledEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminScheduledEventController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $month = $request->query('month', date('Y-m'));

        $query = ScheduledEvent::with(['crewAssignments.user', 'payments', 'expenses'])->latest('start_date');

        if ($status && in_array($status, ['scheduled', 'ongoing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->has('filter_month') && $month) {
            $query->where('start_date', 'like', "{$month}%");
        }

        $events = $query->paginate(12)->withQueryString();

        // Statistik Counter
        $stats = [
            'total' => ScheduledEvent::count(),
            'scheduled' => ScheduledEvent::where('status', 'scheduled')->count(),
            'ongoing' => ScheduledEvent::where('status', 'ongoing')->count(),
            'completed' => ScheduledEvent::where('status', 'completed')->count(),
        ];

        return view('admin.scheduled_events.index', compact('events', 'stats', 'status', 'search', 'month'));
    }

    public function create(Request $request)
    {
        $employees = User::where('usertype', 'karyawan')->orderBy('name')->get();
        $booking = null;

        if ($request->has('booking_id')) {
            $booking = Booking::find($request->booking_id);
        }

        return view('admin.scheduled_events.create', compact('employees', 'booking'));
    }

    public function store(Request $request)
    {
        if ($request->has('contract_amount')) {
            $request->merge(['contract_amount' => preg_replace('/[^0-9]/', '', (string) $request->contract_amount)]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'required|string|max:255',
            'venue_detail' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'contract_amount' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
            'booking_id' => 'nullable|exists:bookings,id',
            'crew' => 'nullable|array',
            'crew.*.crew_type' => 'nullable|in:internal,external',
            'crew.*.user_id' => 'nullable',
            'crew.*.external_name' => 'nullable|string|max:255',
            'crew.*.external_phone' => 'nullable|string|max:50',
            'crew.*.role' => 'nullable|string|max:100',
        ]);

        $event = DB::transaction(function () use ($validated) {
            $event = ScheduledEvent::create([
                'booking_id' => $validated['booking_id'] ?? null,
                'title' => $validated['title'],
                'event_type' => $validated['event_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? $validated['start_date'],
                'start_time' => $validated['start_time'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'location' => $validated['location'],
                'venue_detail' => $validated['venue_detail'] ?? null,
                'client_name' => $validated['client_name'],
                'client_phone' => $validated['client_phone'] ?? null,
                'client_email' => $validated['client_email'] ?? null,
                'contract_amount' => $validated['contract_amount'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Simpan Kru (Internal & Eksternal)
            if (!empty($validated['crew'])) {
                foreach ($validated['crew'] as $item) {
                    $type = ($item['crew_type'] ?? 'internal') === 'external' ? 'external' : 'internal';
                    if ($type === 'internal' && !empty($item['user_id'])) {
                        EventCrew::create([
                            'scheduled_event_id' => $event->id,
                            'user_id' => $item['user_id'],
                            'crew_type' => 'internal',
                            'external_name' => null,
                            'external_phone' => null,
                            'role_in_event' => $item['role'] ?? 'Crew',
                        ]);
                    } elseif ($type === 'external' && !empty($item['external_name'])) {
                        EventCrew::create([
                            'scheduled_event_id' => $event->id,
                            'user_id' => null,
                            'crew_type' => 'external',
                            'external_name' => $item['external_name'],
                            'external_phone' => $item['external_phone'] ?? null,
                            'role_in_event' => $item['role'] ?? 'Crew',
                        ]);
                    }
                }
            }

            // Jika dibuat dari booking, update status booking jadi approved
            if (!empty($validated['booking_id'])) {
                Booking::where('id', $validated['booking_id'])->update(['status' => 'approved']);
            }

            return $event;
        });

        return redirect()->route('admin.scheduled-events.show', $event->id)
            ->with('success', 'Jadwal event berhasil dibuat! Anda sekarang dapat menambahkan rincian keuangan & kru.');
    }

    public function show($id)
    {
        $event = ScheduledEvent::with(['crewAssignments.user', 'payments', 'expenses', 'booking'])->findOrFail($id);
        $employees = User::where('usertype', 'karyawan')->orderBy('name')->get();
        $expenseCategories = EventExpense::getCategories();

        return view('admin.scheduled_events.show', compact('event', 'employees', 'expenseCategories'));
    }

    public function edit($id)
    {
        $event = ScheduledEvent::with('crewAssignments.user')->findOrFail($id);
        $employees = User::where('usertype', 'karyawan')->orderBy('name')->get();

        return view('admin.scheduled_events.edit', compact('event', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $event = ScheduledEvent::findOrFail($id);

        if ($request->has('contract_amount')) {
            $request->merge(['contract_amount' => preg_replace('/[^0-9]/', '', (string) $request->contract_amount)]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'required|string|max:255',
            'venue_detail' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_email' => 'nullable|email|max:255',
            'contract_amount' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
            'crew' => 'nullable|array',
            'crew.*.crew_type' => 'nullable|in:internal,external',
            'crew.*.user_id' => 'nullable',
            'crew.*.external_name' => 'nullable|string|max:255',
            'crew.*.external_phone' => 'nullable|string|max:50',
            'crew.*.role' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($event, $validated) {
            $event->update([
                'title' => $validated['title'],
                'event_type' => $validated['event_type'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? $validated['start_date'],
                'start_time' => $validated['start_time'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'location' => $validated['location'],
                'venue_detail' => $validated['venue_detail'] ?? null,
                'client_name' => $validated['client_name'],
                'client_phone' => $validated['client_phone'] ?? null,
                'client_email' => $validated['client_email'] ?? null,
                'contract_amount' => $validated['contract_amount'],
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Simpan / Perbarui Kru (Hapus daftar lama dan masukkan daftar terkini)
            EventCrew::where('scheduled_event_id', $event->id)->delete();
            if (!empty($validated['crew'])) {
                foreach ($validated['crew'] as $item) {
                    $type = ($item['crew_type'] ?? 'internal') === 'external' ? 'external' : 'internal';
                    if ($type === 'internal' && !empty($item['user_id'])) {
                        EventCrew::create([
                            'scheduled_event_id' => $event->id,
                            'user_id' => $item['user_id'],
                            'crew_type' => 'internal',
                            'external_name' => null,
                            'external_phone' => null,
                            'role_in_event' => $item['role'] ?? 'Crew',
                        ]);
                    } elseif ($type === 'external' && !empty($item['external_name'])) {
                        EventCrew::create([
                            'scheduled_event_id' => $event->id,
                            'user_id' => null,
                            'crew_type' => 'external',
                            'external_name' => $item['external_name'],
                            'external_phone' => $item['external_phone'] ?? null,
                            'role_in_event' => $item['role'] ?? 'Crew',
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.scheduled-events.show', $event->id)
            ->with('success', 'Jadwal event dan kru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $event = ScheduledEvent::findOrFail($id);
        $title = $event->title;
        $event->delete();

        return redirect()->route('admin.scheduled-events.index')
            ->with('success', "Event '{$title}' dan seluruh data keuangannya telah dihapus.");
    }

    public function convertFromBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        return redirect()->route('admin.scheduled-events.create', ['booking_id' => $booking->id]);
    }

    /**
     * Unduh Laporan Rekapitulasi Event & Finansial dalam Format Excel (.xls)
     */
    public function exportExcel(Request $request)
    {
        $events = $this->getFilteredEvents($request);
        $periodLabel = $this->getEventPeriodLabel($request);
        $statusFilter = $request->query('status');

        $cleanPeriod = preg_replace('/[^A-Za-z0-9\-]/', '_', $periodLabel);
        $filename = 'Laporan-Event-Keuangan-Stukka-' . $cleanPeriod . '-' . date('Ymd_His') . '.xls';

        $content = view('admin.scheduled_events.export_excel', compact(
            'events',
            'periodLabel',
            'statusFilter'
        ))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Tampilan Laporan Rekapitulasi Event & Keuangan Cetak / Simpan PDF (A4 Landscape Print-Ready)
     */
    public function exportPdf(Request $request)
    {
        $events = $this->getFilteredEvents($request);
        $periodLabel = $this->getEventPeriodLabel($request);
        $statusFilter = $request->query('status');

        $totalContracts = $events->sum('contract_amount');
        $totalReceived = $events->sum('total_paid');
        $totalReceivable = $events->sum('remaining_receivable');
        $totalExpenses = $events->sum('total_expense');
        $totalPaidExpenses = $events->sum('total_paid_expense');
        $totalUnpaidExpenses = $events->sum('remaining_expense');
        $totalNetProfit = $events->sum('net_profit');

        return view('admin.scheduled_events.export_pdf', compact(
            'events',
            'periodLabel',
            'statusFilter',
            'totalContracts',
            'totalReceived',
            'totalReceivable',
            'totalExpenses',
            'totalPaidExpenses',
            'totalUnpaidExpenses',
            'totalNetProfit'
        ));
    }

    /**
     * Cetak Lembar Kerja & Rincian Tunggal Event (SPK & Detail Keuangan)
     */
    public function printSingleEvent($id)
    {
        $event = ScheduledEvent::with(['crewAssignments.user', 'payments', 'expenses'])->findOrFail($id);
        return view('admin.scheduled_events.print_single', compact('event'));
    }

    /**
     * Helper: Ambil seluruh data event terfilter untuk ekspor
     */
    private function getFilteredEvents(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $month = $request->query('month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = ScheduledEvent::with(['crewAssignments.user', 'payments', 'expenses'])->latest('start_date');

        if ($status && in_array($status, ['scheduled', 'ongoing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        } elseif ($month) {
            $query->where('start_date', 'like', "{$month}%");
        }

        return $query->get();
    }

    /**
     * Helper: Format label periode event
     */
    private function getEventPeriodLabel(Request $request)
    {
        if ($request->query('start_date') && $request->query('end_date')) {
            return \Carbon\Carbon::parse($request->query('start_date'))->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($request->query('end_date'))->translatedFormat('d M Y');
        } elseif ($request->query('month')) {
            return \Carbon\Carbon::parse($request->query('month') . '-01')->translatedFormat('F Y');
        }
        return 'Semua Data s/d ' . \Carbon\Carbon::today()->translatedFormat('d F Y');
    }
}
