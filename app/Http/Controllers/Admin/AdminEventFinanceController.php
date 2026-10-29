<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventExpense;
use App\Models\EventPayment;
use App\Models\ScheduledEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventFinanceController extends Controller
{
    /**
     * Dashboard & Rekapitulasi Keuangan Seluruh Event
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $month = $request->query('month');
        $clientStatus = $request->query('client_status'); // unpaid, partial, paid
        $vendorStatus = $request->query('vendor_status'); // unpaid, partial, paid

        $query = ScheduledEvent::with(['payments', 'expenses'])->latest('start_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($month) {
            $query->where('start_date', 'like', "{$month}%");
        }

        $allEvents = $query->get();

        // Filter status client / vendor jika ada di query
        if ($clientStatus) {
            $allEvents = $allEvents->filter(function ($event) use ($clientStatus) {
                return $event->client_payment_status === $clientStatus;
            });
        }

        if ($vendorStatus) {
            $allEvents = $allEvents->filter(function ($event) use ($vendorStatus) {
                return $event->vendor_payment_status === $vendorStatus;
            });
        }

        // Global Financial Metrics
        $totalContracts = $allEvents->sum('contract_amount');
        $totalReceived = $allEvents->sum('total_paid');
        $totalReceivable = $allEvents->sum('remaining_receivable');
        $totalExpenses = $allEvents->sum('total_expense');
        $totalPaidExpenses = $allEvents->sum('total_paid_expense');
        $totalUnpaidExpenses = $allEvents->sum('remaining_expense');
        $totalNetProfit = $allEvents->sum('net_profit');

        // Profit margin global
        $globalMargin = $totalContracts > 0 ? round(($totalNetProfit / $totalContracts) * 100, 1) : 0;

        // Paginate manually from collection
        $page = $request->get('page', 1);
        $perPage = 10;
        $paginatedEvents = new \Illuminate\Pagination\LengthAwarePaginator(
            $allEvents->forPage($page, $perPage),
            $allEvents->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.finance.index', [
            'events' => $paginatedEvents,
            'totalContracts' => $totalContracts,
            'totalReceived' => $totalReceived,
            'totalReceivable' => $totalReceivable,
            'totalExpenses' => $totalExpenses,
            'totalPaidExpenses' => $totalPaidExpenses,
            'totalUnpaidExpenses' => $totalUnpaidExpenses,
            'totalNetProfit' => $totalNetProfit,
            'globalMargin' => $globalMargin,
            'search' => $search,
            'month' => $month,
            'clientStatus' => $clientStatus,
            'vendorStatus' => $vendorStatus,
        ]);
    }

    /**
     * Catat pembayaran masuk dari klien
     */
    public function storePayment(Request $request, $eventId)
    {
        $event = ScheduledEvent::findOrFail($eventId);

        if ($request->has('amount')) {
            $request->merge(['amount' => preg_replace('/[^0-9]/', '', (string) $request->amount)]);
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt_proof' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt_proof')) {
            $receiptPath = $request->file('receipt_proof')->store('event_receipts', 'public');
        }

        $event->payments()->create([
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_no' => $validated['reference_no'] ?? null,
            'receipt_proof' => $receiptPath,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.scheduled-events.show', ['scheduled_event' => $event->id, 'tab' => 'finance'])
            ->with('success', 'Pembayaran klien sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil dicatat.');
    }

    /**
     * Hapus pembayaran klien
     */
    public function destroyPayment($id)
    {
        $payment = EventPayment::findOrFail($id);
        $eventId = $payment->scheduled_event_id;

        if ($payment->receipt_proof) {
            Storage::disk('public')->delete($payment->receipt_proof);
        }

        $payment->delete();

        return redirect()->route('admin.scheduled-events.show', ['scheduled_event' => $eventId, 'tab' => 'finance'])
            ->with('success', 'Data pembayaran klien berhasil dihapus.');
    }

    /**
     * Catat biaya produksi vendor / operasional event
     */
    public function storeExpense(Request $request, $eventId)
    {
        $event = ScheduledEvent::findOrFail($eventId);

        if ($request->has('amount')) {
            $request->merge(['amount' => preg_replace('/[^0-9]/', '', (string) $request->amount)]);
        }

        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'item_name' => 'required|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1',
            'is_paid' => 'required|boolean',
            'paid_at' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'invoice_proof' => 'nullable|image|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ]);

        $invoicePath = null;
        if ($request->hasFile('invoice_proof')) {
            $invoicePath = $request->file('invoice_proof')->store('event_invoices', 'public');
        }

        $isPaid = (bool) $validated['is_paid'];
        $paidAt = $isPaid ? ($validated['paid_at'] ?? now()) : null;

        $event->expenses()->create([
            'category' => $validated['category'],
            'item_name' => $validated['item_name'],
            'vendor_name' => $validated['vendor_name'] ?? null,
            'amount' => $validated['amount'],
            'is_paid' => $isPaid,
            'paid_at' => $paidAt,
            'payment_method' => $validated['payment_method'] ?? null,
            'invoice_proof' => $invoicePath,
            'notes' => $validated['notes'] ?? null,
        ]);

        $statusText = $isPaid ? 'Lunas' : 'Belum Lunas (Hutang Vendor)';
        return redirect()->route('admin.scheduled-events.show', ['scheduled_event' => $event->id, 'tab' => 'finance'])
            ->with('success', "Biaya produksi '{$validated['item_name']}' ({$statusText}) berhasil dicatat.");
    }

    /**
     * Toggle status pembayaran biaya produksi (Lunas <-> Belum Lunas)
     */
    public function toggleExpenseStatus($id)
    {
        $expense = EventExpense::findOrFail($id);
        $expense->is_paid = !$expense->is_paid;
        $expense->paid_at = $expense->is_paid ? now() : null;
        $expense->save();

        $statusText = $expense->is_paid ? 'LUNAS' : 'BELUM LUNAS (Hutang Vendor)';
        return redirect()->route('admin.scheduled-events.show', ['scheduled_event' => $expense->scheduled_event_id, 'tab' => 'finance'])
            ->with('success', "Status biaya '{$expense->item_name}' diubah menjadi {$statusText}.");
    }

    /**
     * Hapus biaya produksi
     */
    public function destroyExpense($id)
    {
        $expense = EventExpense::findOrFail($id);
        $eventId = $expense->scheduled_event_id;

        if ($expense->invoice_proof) {
            Storage::disk('public')->delete($expense->invoice_proof);
        }

        $expense->delete();

        return redirect()->route('admin.scheduled-events.show', ['scheduled_event' => $eventId, 'tab' => 'finance'])
            ->with('success', 'Biaya produksi berhasil dihapus.');
    }
}
