<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\LoanInstallment;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_name',
        'lender',
        'principal_amount',
        'interest_rate',
        'interest_type',
        'term_months',
        'monthly_payment',
        'repayment_strategy',
        'planned_extra_payment',
        'balloon_payment',
        'start_date',
        'due_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'principal_amount' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'monthly_payment' => 'decimal:2',
            'planned_extra_payment' => 'decimal:2',
            'balloon_payment' => 'decimal:2',
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculated Values
    |--------------------------------------------------------------------------
    */

    public function getTotalPayableAttribute(): float
    {
        $totalPayable = (float) $this->principal_amount;

        if (in_array($this->interest_type, ['simple', 'fixed'], true)) {
            $totalPayable +=
                $totalPayable * ((float) $this->interest_rate / 100);
        }

        return $totalPayable;
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(
            0,
            $this->total_payable - $this->total_paid
        );
    }

    public function getPaymentProgressAttribute(): float
    {
        if ($this->total_payable <= 0) {
            return 0;
        }

        return min(
            100,
            round(
                ($this->total_paid / $this->total_payable) * 100,
                2
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Installment Helpers
    |--------------------------------------------------------------------------
    */

    public function generateInstallments(): void
    {
        $this->installments()->delete();

        $termMonths = max(
            1,
            (int) ($this->term_months ?? 1)
        );

        $monthlyPayment = round(
            (float) $this->monthly_payment,
            2
        );

        if ($monthlyPayment <= 0) {
            return;
        }

        $startDate = $this->start_date
            ? $this->start_date->copy()
            : Carbon::today();

        $totalPayable = $this->total_payable;

        /*
        |--------------------------------------------------------------------------
        | Create Normal Installments
        |--------------------------------------------------------------------------
        */

        $runningTotal = 0;

        for ($i = 1; $i <= $termMonths; $i++) {

            $dueDate = $startDate
                ->copy()
                ->addMonthsNoOverflow($i);

            /*
            |--------------------------------------------------------------------------
            | Last installment absorbs rounding difference.
            |--------------------------------------------------------------------------
            */

            if ($i === $termMonths) {

                $amountDue = round(
                    $totalPayable - $runningTotal,
                    2
                );

            } else {

                $amountDue = $monthlyPayment;
            }

            $amountDue = max(0, $amountDue);

            LoanInstallment::create([
                'loan_id' => $this->id,
                'installment_number' => $i,
                'due_date' => $dueDate->toDateString(),
                'amount_due' => $amountDue,
                'amount_paid' => 0,
                'status' => $dueDate->isPast()
                    ? 'overdue'
                    : 'upcoming',
                'paid_at' => null,
            ]);

            $runningTotal += $amountDue;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Existing Installments' Status
        |--------------------------------------------------------------------------
        */

        $this->refreshInstallmentStatuses();
    }


    public function refreshInstallmentStatuses(): void
    {
        $today = \Carbon\Carbon::today();

        foreach ($this->installments()->get() as $installment) {

            $remaining = $installment->remaining_amount;

            /*
            |--------------------------------------------------------------------------
            | Fully Paid
            |--------------------------------------------------------------------------
            */

            if ($remaining <= 0) {

                $installment->update([
                    'status' => 'paid',
                ]);

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Partially Paid
            |--------------------------------------------------------------------------
            */

            if ((float) $installment->amount_paid > 0) {

                $installment->update([
                    'status' => $installment->due_date->lt($today)
                        ? 'overdue'
                        : 'partial',

                    'paid_at' => null,
                ]);

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Overdue
            |--------------------------------------------------------------------------
            */

            if ($installment->due_date->lt($today)) {

                $installment->update([
                    'status' => 'overdue',
                    'paid_at' => null,
                ]);

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Due Today
            |--------------------------------------------------------------------------
            */

            if ($installment->due_date->isToday()) {

                $installment->update([
                    'status' => 'due_today',
                    'paid_at' => null,
                ]);

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Upcoming
            |--------------------------------------------------------------------------
            */

            $installment->update([
                'status' => 'upcoming',
                'paid_at' => null,
            ]);
        }
    }

    public function updateStatus(): void
    {
        $this->refreshInstallmentStatuses();

        /*
        |--------------------------------------------------------------------------
        | Fully Paid
        |--------------------------------------------------------------------------
        */

        if ($this->remaining_balance <= 0) {

            $this->update([
                'status' => 'completed',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Overdue
        |--------------------------------------------------------------------------
        */

        $hasOverdueInstallment = $this->installments()
            ->where('status', 'overdue')
            ->exists();

        if ($hasOverdueInstallment) {

            $this->update([
                'status' => 'overdue',
            ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Active
        |--------------------------------------------------------------------------
        */

        $this->update([
            'status' => 'active',
        ]);
    }
}