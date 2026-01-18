<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Donasi;
use App\Models\Program;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Midtrans\Snap;
use Mockery;

class DonasiOnlineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake email
        Mail::fake();

        // Mock Snap Midtrans
        Mockery::mock('alias:' . Snap::class)
            ->shouldReceive('getSnapToken')
            ->andReturn('dummy-snap-token');
    }

    #[Test]
    public function user_can_open_payment_page_for_pending_donation()
    {
        $program = Program::factory()->create();

        $donasi = Donasi::factory()->create([
            'program_id' => $program->id,
            'status'     => 'pending',
            'nominal'    => 100000,
        ]);

        $response = $this->get(route('transaction.pay', $donasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('midtrans.snap');
        $response->assertViewHas('snapToken');
    }

    #[Test]
    public function cannot_pay_donation_that_is_not_pending()
    {
        $donasi = Donasi::factory()->create([
            'status' => 'paid',
        ]);

        $response = $this->get(route('transaction.pay', $donasi->id));

        $response->assertStatus(403);
    }

    #[Test]
    public function transaction_record_is_created_when_payment_started()
    {
        $donasi = Donasi::factory()->create([
            'status'  => 'pending',
            'nominal' => 50000,
        ]);

        $this->get(route('transaction.pay', $donasi->id));

        $this->assertDatabaseCount('transactions', 1);

        $this->assertDatabaseHas('transactions', [
            'donasi_id' => $donasi->id,
            'status'    => 'pending',
            'amount'    => 50000,
        ]);
    }

    #[Test]
    public function midtrans_callback_can_mark_transaction_as_paid()
    {
        $donasi = Donasi::factory()->create([
            'status'  => 'pending',
            'nominal' => 150000,
        ]);

        $transaction = Transaction::factory()->create([
            'donasi_id' => $donasi->id,
            'reference' => 'DON-TEST-123',
            'status'    => 'pending',
            'amount'    => 150000,
        ]);

        $payload = [
            'order_id'            => 'DON-TEST-123',
            'transaction_status'  => 'settlement',
            'payment_type'        => 'bank_transfer',
            'va_numbers' => [
                ['bank' => 'bca']
            ],
        ];

        $response = $this->postJson('/midtrans/callback', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'id'             => $transaction->id,
            'status'         => 'paid',
            'payment_method' => 'bca',
        ]);

        $this->assertDatabaseHas('donasis', [
            'id'     => $donasi->id,
            'status' => 'paid',
        ]);
    }

    #[Test]
    public function midtrans_callback_can_mark_transaction_as_expired()
    {
        $donasi = Donasi::factory()->create(['status' => 'pending']);

        $transaction = Transaction::factory()->create([
            'donasi_id' => $donasi->id,
            'reference' => 'DON-EXPIRED',
            'status'    => 'pending',
        ]);

        $payload = [
            'order_id'           => 'DON-EXPIRED',
            'transaction_status' => 'expire',
            'payment_type'       => 'qris',
        ];

        $this->postJson('/midtrans/callback', $payload);

        $this->assertDatabaseHas('transactions', [
            'id'     => $transaction->id,
            'status' => 'expired',
        ]);

        $this->assertDatabaseHas('donasis', [
            'id'     => $donasi->id,
            'status' => 'expired',
        ]);
    }

    #[Test]
    public function cannot_continue_payment_if_transaction_not_pending()
    {
        $transaction = Transaction::factory()->create([
            'status' => 'paid',
        ]);

        $response = $this->get(route('payment.back', $transaction->id));

        $response->assertStatus(403);
    }
}
