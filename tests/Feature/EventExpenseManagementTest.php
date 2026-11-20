<?php

namespace Tests\Feature;

use App\Models\EventExpense;
use App\Models\ScheduledEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected ScheduledEvent $event;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'usertype' => 'admin',
            'role' => User::ROLE_FULL_ACCESS,
            'roles' => [User::ROLE_FULL_ACCESS],
        ]);

        $this->event = ScheduledEvent::create([
            'title' => 'Wedding Test Event',
            'event_type' => 'wedding',
            'status' => 'scheduled',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '17:00',
            'location' => 'Jakarta',
            'client_name' => 'Klien Test',
            'contract_amount' => 50000000,
        ]);
    }

    public function test_admin_can_store_expense_with_invoice_and_payment_proof(): void
    {
        $invoiceFile = UploadedFile::fake()->create('invoice_vendor.pdf', 500, 'application/pdf');
        $paymentFile = UploadedFile::fake()->image('transfer_receipt.jpg');

        $response = $this->actingAs($this->admin)->post(route('admin.finance.expense.store', $this->event->id), [
            'category' => 'sound',
            'item_name' => 'Sewa Sound System Line Array',
            'vendor_name' => 'Vendor Audio Pro',
            'amount' => '15.000.000',
            'is_paid' => '1',
            'payment_method' => 'Transfer Bank BCA',
            'paid_at' => now()->format('Y-m-d'),
            'notes' => 'Termasuk genset dan operator',
            'invoice_proof' => $invoiceFile,
            'payment_proof' => $paymentFile,
        ]);

        $response->assertRedirect(route('admin.scheduled-events.show', ['scheduled_event' => $this->event->id, 'tab' => 'finance']));

        $expense = EventExpense::first();
        $this->assertNotNull($expense);
        $this->assertEquals('Sewa Sound System Line Array', $expense->expense_name);
        $this->assertEquals('Vendor Audio Pro', $expense->vendor_name);
        $this->assertEquals(15000000, $expense->amount);
        $this->assertEquals('paid', $expense->status);
        $this->assertNotNull($expense->invoice_proof);
        $this->assertNotNull($expense->payment_proof);
        Storage::disk('public')->assertExists($expense->invoice_proof);
        Storage::disk('public')->assertExists($expense->payment_proof);
    }

    public function test_admin_can_update_expense_and_upload_payment_proof(): void
    {
        $expense = $this->event->expenses()->create([
            'expense_name' => 'Sewa Panggung & Tenda',
            'category' => 'stage',
            'vendor_name' => 'Vendor Stage Jaya',
            'amount' => 10000000,
            'status' => 'unpaid',
            'due_date' => now()->addDays(2)->format('Y-m-d'),
        ]);

        $this->assertEquals('unpaid', $expense->status);
        $this->assertNull($expense->payment_proof);

        $paymentFile = UploadedFile::fake()->image('bukti_transfer_lunas.png');

        $response = $this->actingAs($this->admin)->put(route('admin.finance.expense.update', $expense->id), [
            'category' => 'stage',
            'item_name' => 'Sewa Panggung & Tenda (Revisi)',
            'vendor_name' => 'Vendor Stage Jaya',
            'amount' => '10.000.000',
            'is_paid' => '1',
            'payment_method' => 'Transfer Bank Mandiri',
            'paid_at' => now()->format('Y-m-d'),
            'notes' => 'Sudah ditransfer lunas',
            'payment_proof' => $paymentFile,
        ]);

        $response->assertRedirect(route('admin.scheduled-events.show', ['scheduled_event' => $this->event->id, 'tab' => 'finance']));

        $expense->refresh();
        $this->assertEquals('Sewa Panggung & Tenda (Revisi)', $expense->expense_name);
        $this->assertEquals('paid', $expense->status);
        $this->assertEquals('Transfer Bank Mandiri', $expense->payment_method);
        $this->assertNotNull($expense->payment_proof);
        Storage::disk('public')->assertExists($expense->payment_proof);
    }
}
