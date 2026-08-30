<?php

namespace App\Services;

use App\Models\Income;
use App\Models\LoanPayment;
use App\Models\SalaryPeriod;

class AvailableFundsService
{
    /**
     * Income sources that can be used to fund loan payments.
     */
    public const INCOME_SOURCES = [
        'Salary',
        'Side Income',
        'Freelance',
        'Other Income',
    ];

    /**
     * Get total salary received up to a specific date.
     */
    public function totalSalary(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        return (float) SalaryPeriod::where('user_id', $userId)
            ->whereDate('salary_date', '<=', $date)
            ->sum('salary_amount');
    }

    /**
     * Get total additional income received up to a specific date.
     */
    public function totalAdditionalIncome(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        return (float) Income::where('user_id', $userId)
            ->whereDate('income_date', '<=', $date)
            ->sum('amount');
    }

    /**
     * Get total loan payments funded by salary.
     */
    public function totalSalaryFundedPayments(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        return (float) LoanPayment::where('user_id', $userId)
            ->where('payment_source', 'Salary')
            ->whereDate('payment_date', '<=', $date)
            ->sum('amount');
    }

    /**
     * Get total loan payments funded by additional income.
     */
    public function totalAdditionalIncomeFundedPayments(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        return (float) LoanPayment::where('user_id', $userId)
            ->whereIn('payment_source', [
                'Side Income',
                'Freelance',
                'Other Income',
            ])
            ->whereDate('payment_date', '<=', $date)
            ->sum('amount');
    }

    /**
     * Get total loan payments funded by income.
     */
    public function totalIncomeFundedPayments(
        int $userId,
        ?string $date = null
    ): float {
        return $this->totalSalaryFundedPayments(
            $userId,
            $date
        )
        +
        $this->totalAdditionalIncomeFundedPayments(
            $userId,
            $date
        );
    }

    /**
     * Get total available funds.
     *
     * Formula:
     *
     * Salary
     * + Additional Income
     * - Income-funded Loan Payments
     */
    public function availableFunds(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        $salary = $this->totalSalary(
            $userId,
            $date
        );

        $additionalIncome = $this->totalAdditionalIncome(
            $userId,
            $date
        );

        $incomeFundedPayments = $this->totalIncomeFundedPayments(
            $userId,
            $date
        );

        return max(
            0,
            $salary
            + $additionalIncome
            - $incomeFundedPayments
        );
    }

    /**
     * Get available salary only.
     */
    public function availableSalary(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        $salary = $this->totalSalary(
            $userId,
            $date
        );

        $salaryPayments = $this->totalSalaryFundedPayments(
            $userId,
            $date
        );

        return max(
            0,
            $salary - $salaryPayments
        );
    }

    /**
     * Get available additional income only.
     */
    public function availableAdditionalIncome(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        $additionalIncome = $this->totalAdditionalIncome(
            $userId,
            $date
        );

        $additionalIncomePayments =
            $this->totalAdditionalIncomeFundedPayments(
                $userId,
                $date
            );

        return max(
            0,
            $additionalIncome - $additionalIncomePayments
        );
    }

    /**
     * Get income usage percentage.
     */
    public function usagePercentage(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        $totalFunds =
            $this->totalSalary(
                $userId,
                $date
            )
            +
            $this->totalAdditionalIncome(
                $userId,
                $date
            );

        if ($totalFunds <= 0) {
            return 0;
        }

        $used = $this->totalIncomeFundedPayments(
            $userId,
            $date
        );

        return min(
            100,
            round(
                ($used / $totalFunds) * 100,
                1
            )
        );
    }

    /**
     * Get salary usage percentage.
     */
    public function salaryUsagePercentage(
        int $userId,
        ?string $date = null
    ): float {
        $date = $date ?: now()->toDateString();

        $salary = $this->totalSalary(
            $userId,
            $date
        );

        if ($salary <= 0) {
            return 0;
        }

        $used = $this->totalSalaryFundedPayments(
            $userId,
            $date
        );

        return min(
            100,
            round(
                ($used / $salary) * 100,
                1
            )
        );
    }
}