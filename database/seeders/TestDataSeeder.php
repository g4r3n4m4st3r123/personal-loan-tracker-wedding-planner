<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Salary;
use App\Models\SalaryPeriod;
use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingBudget;
use App\Models\WeddingExpense;
use App\Models\WeddingGuest;
use App\Models\WeddingTask;
use App\Models\WeddingTimelineItem;
use App\Models\WeddingVendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TEST USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);


        /*
        |--------------------------------------------------------------------------
        | SALARY HISTORY
        |--------------------------------------------------------------------------
        */

        Salary::create([
            'user_id' => $user->id,
            'amount' => 20000,
            'effective_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'salary_type' => 'monthly',
            'notes' => 'Previous monthly salary',
        ]);

        Salary::create([
            'user_id' => $user->id,
            'amount' => 22000,
            'effective_date' => now()->startOfMonth()->toDateString(),
            'salary_type' => 'monthly',
            'notes' => 'Current monthly salary',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SALARY PERIODS
        |--------------------------------------------------------------------------
        */

        SalaryPeriod::create([
            'user_id' => $user->id,
            'salary_amount' => 20000,
            'carry_over' => 2500,
            'period_start' => now()->subMonth()->startOfMonth()->toDateString(),
            'period_end' => now()->subMonth()->endOfMonth()->toDateString(),
            'salary_date' => now()->subMonth()->startOfMonth()->toDateString(),
            'salary_type' => 'monthly',
            'status' => 'completed',
            'notes' => 'Previous salary period',
        ]);

        SalaryPeriod::create([
            'user_id' => $user->id,
            'salary_amount' => 22000,
            'carry_over' => 2500,
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'salary_date' => now()->startOfMonth()->toDateString(),
            'salary_type' => 'monthly',
            'status' => 'current',
            'notes' => 'Current salary period',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL INCOME
        |--------------------------------------------------------------------------
        */

        Income::create([
            'user_id' => $user->id,
            'income_type' => 'Side Income',
            'amount' => 3500,
            'income_date' => now()->subDays(5)->toDateString(),
            'description' => 'Graphic design side project',
        ]);

        Income::create([
            'user_id' => $user->id,
            'income_type' => 'Freelance',
            'amount' => 5000,
            'income_date' => now()->subDays(3)->toDateString(),
            'description' => 'Freelance website project',
        ]);

        Income::create([
            'user_id' => $user->id,
            'income_type' => 'Other Income',
            'amount' => 1500,
            'income_date' => now()->subDay()->toDateString(),
            'description' => 'Other earnings',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PERSONAL EXPENSES
        |--------------------------------------------------------------------------
        */

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Food',
            'amount' => 850,
            'expense_date' => now()->subDays(3)->toDateString(),
            'description' => 'Weekly groceries',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Transportation',
            'amount' => 500,
            'expense_date' => now()->subDays(2)->toDateString(),
            'description' => 'Fuel and transportation',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Bills',
            'amount' => 1800,
            'expense_date' => now()->subDay()->toDateString(),
            'description' => 'Electric and internet',
        ]);

        Expense::create([
            'user_id' => $user->id,
            'category' => 'Shopping',
            'amount' => 1200,
            'expense_date' => now()->toDateString(),
            'description' => 'Household supplies',
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOANS
        |--------------------------------------------------------------------------
        */

        $loan1 = Loan::create([
            'user_id' => $user->id,
            'loan_name' => 'BDO Personal Loan',
            'lender' => 'BDO',
            'principal_amount' => 30000,
            'interest_rate' => 10,
            'interest_type' => 'simple',
            'term_months' => 12,
            'monthly_payment' => 2750,
            'start_date' => now()->subMonths(3)->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'active',
            'notes' => 'Test loan due soon',
        ]);

        $loan2 = Loan::create([
            'user_id' => $user->id,
            'loan_name' => 'Friend Emergency Loan',
            'lender' => 'Maria Santos',
            'principal_amount' => 10000,
            'interest_rate' => 0,
            'interest_type' => 'none',
            'term_months' => 10,
            'monthly_payment' => 1000,
            'start_date' => now()->subMonths(2)->toDateString(),
            'due_date' => now()->subDays(2)->toDateString(),
            'status' => 'overdue',
            'notes' => 'Test overdue loan',
        ]);

        $loan3 = Loan::create([
            'user_id' => $user->id,
            'loan_name' => 'Laptop Loan',
            'lender' => 'Home Credit',
            'principal_amount' => 20000,
            'interest_rate' => 5,
            'interest_type' => 'fixed',
            'term_months' => 10,
            'monthly_payment' => 2100,
            'start_date' => now()->subMonth()->toDateString(),
            'due_date' => now()->addDays(10)->toDateString(),
            'status' => 'active',
            'notes' => 'Future payment test',
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOAN PAYMENTS
        |--------------------------------------------------------------------------
        */

        LoanPayment::create([
            'user_id' => $user->id,
            'loan_id' => $loan1->id,
            'amount' => 2750,
            'payment_date' => now()->subDays(10)->toDateString(),
            'payment_method' => 'Salary Deduction',
            'payment_source' => 'Salary',
            'reference_number' => 'TEST-SAL-001',
            'notes' => 'Salary deduction payment',
        ]);

        LoanPayment::create([
            'user_id' => $user->id,
            'loan_id' => $loan2->id,
            'amount' => 1000,
            'payment_date' => now()->subDays(20)->toDateString(),
            'payment_method' => 'Cash',
            'payment_source' => 'Other',
            'reference_number' => 'TEST-CASH-001',
            'notes' => 'Cash payment',
        ]);

        LoanPayment::create([
            'user_id' => $user->id,
            'loan_id' => $loan3->id,
            'amount' => 2100,
            'payment_date' => now()->subDays(7)->toDateString(),
            'payment_method' => 'Salary Deduction',
            'payment_source' => 'Salary',
            'reference_number' => 'TEST-SAL-002',
            'notes' => 'Salary deduction payment',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING
        |--------------------------------------------------------------------------
        */

        $wedding = Wedding::create([
            'user_id' => $user->id,
            'wedding_name' => 'Ivy & John Wedding',
            'partner_name' => 'John',
            'wedding_date' => now()->addDays(20)->toDateString(),
            'venue' => 'Garden Pavilion, Davao City',
            'budget' => 150000,
            'notes' => 'Test wedding for final QA',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING BUDGET
        |--------------------------------------------------------------------------
        */

        $venueBudget = WeddingBudget::create([
            'wedding_id' => $wedding->id,
            'category' => 'Venue',
            'planned_amount' => 40000,
            'actual_amount' => 0,
            'notes' => 'Garden venue',
        ]);

        $cateringBudget = WeddingBudget::create([
            'wedding_id' => $wedding->id,
            'category' => 'Catering',
            'planned_amount' => 50000,
            'actual_amount' => 0,
            'notes' => 'Food and drinks',
        ]);

        WeddingBudget::create([
            'wedding_id' => $wedding->id,
            'category' => 'Photography',
            'planned_amount' => 20000,
            'actual_amount' => 0,
            'notes' => 'Photo and video',
        ]);

        WeddingBudget::create([
            'wedding_id' => $wedding->id,
            'category' => 'Attire',
            'planned_amount' => 15000,
            'actual_amount' => 0,
            'notes' => 'Bride and groom attire',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING EXPENSES
        |--------------------------------------------------------------------------
        */

        WeddingExpense::create([
            'wedding_id' => $wedding->id,
            'wedding_budget_id' => $cateringBudget->id,
            'expense_name' => 'Catering Down Payment',
            'amount' => 20000,
            'expense_date' => now()->subDays(4)->toDateString(),
            'payment_status' => 'paid',
            'payment_method' => 'GCash',
            'notes' => 'Initial catering payment',
        ]);

        WeddingExpense::create([
            'wedding_id' => $wedding->id,
            'wedding_budget_id' => $venueBudget->id,
            'expense_name' => 'Venue Reservation',
            'amount' => 15000,
            'expense_date' => now()->subDays(2)->toDateString(),
            'payment_status' => 'paid',
            'payment_method' => 'Bank Transfer',
            'notes' => 'Venue reservation fee',
        ]);

        WeddingExpense::create([
            'wedding_id' => $wedding->id,
            'wedding_budget_id' => null,
            'expense_name' => 'Wedding Invitations',
            'amount' => 5000,
            'expense_date' => now()->toDateString(),
            'payment_status' => 'pending',
            'payment_method' => null,
            'notes' => 'Printing balance',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING GUESTS
        |--------------------------------------------------------------------------
        */

        WeddingGuest::create([
            'wedding_id' => $wedding->id,
            'name' => 'Maria Santos',
            'guest_type' => 'friend',
            'rsvp_status' => 'attending',
            'meal_preference' => 'Regular',
            'phone' => '09171234567',
            'email' => 'maria@example.com',
            'plus_one' => true,
            'notes' => 'College friend',
        ]);

        WeddingGuest::create([
            'wedding_id' => $wedding->id,
            'name' => 'Pedro Santos',
            'guest_type' => 'family',
            'rsvp_status' => 'pending',
            'meal_preference' => 'Vegetarian',
            'phone' => '09181234567',
            'email' => 'pedro@example.com',
            'plus_one' => false,
            'notes' => 'Waiting for confirmation',
        ]);

        WeddingGuest::create([
            'wedding_id' => $wedding->id,
            'name' => 'Ana Cruz',
            'guest_type' => 'bride_side',
            'rsvp_status' => 'declined',
            'meal_preference' => 'Regular',
            'phone' => '09191234567',
            'email' => 'ana@example.com',
            'plus_one' => false,
            'notes' => 'Cannot attend',
        ]);

        WeddingGuest::create([
            'wedding_id' => $wedding->id,
            'name' => 'Mark Reyes',
            'guest_type' => 'groom_side',
            'rsvp_status' => 'attending',
            'meal_preference' => 'Kids Meal',
            'phone' => '09201234567',
            'email' => 'mark@example.com',
            'plus_one' => false,
            'notes' => null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING VENDORS
        |--------------------------------------------------------------------------
        */

        WeddingVendor::create([
            'wedding_id' => $wedding->id,
            'vendor_name' => 'Elegant Events Catering',
            'service_type' => 'Catering',
            'contact_person' => 'Maria Cruz',
            'phone' => '09175551234',
            'email' => 'catering@example.com',
            'address' => 'Davao City',
            'agreed_amount' => 50000,
            'amount_paid' => 20000,
            'payment_status' => 'partial',
            'booking_date' => now()->subMonth()->toDateString(),
            'service_date' => now()->addDays(20)->toDateString(),
            'notes' => 'Full catering package',
        ]);

        WeddingVendor::create([
            'wedding_id' => $wedding->id,
            'vendor_name' => 'Forever Frames Studio',
            'service_type' => 'Photography',
            'contact_person' => 'John Cruz',
            'phone' => '09176667890',
            'email' => 'photo@example.com',
            'address' => 'Davao City',
            'agreed_amount' => 20000,
            'amount_paid' => 20000,
            'payment_status' => 'paid',
            'booking_date' => now()->subMonth()->toDateString(),
            'service_date' => now()->addDays(20)->toDateString(),
            'notes' => 'Photo and video package',
        ]);

        WeddingVendor::create([
            'wedding_id' => $wedding->id,
            'vendor_name' => 'Dream Decor',
            'service_type' => 'Decoration',
            'contact_person' => 'Angela Tan',
            'phone' => '09178889999',
            'email' => 'decor@example.com',
            'address' => 'Davao City',
            'agreed_amount' => 30000,
            'amount_paid' => 5000,
            'payment_status' => 'partial',
            'booking_date' => now()->subDays(10)->toDateString(),
            'service_date' => now()->addDays(15)->toDateString(),
            'notes' => 'Reception decoration',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING CHECKLIST
        |--------------------------------------------------------------------------
        */

        WeddingTask::create([
            'wedding_id' => $wedding->id,
            'task_name' => 'Book Wedding Photographer',
            'due_date' => now()->addDays(3)->toDateString(),
            'priority' => 'high',
            'status' => 'pending',
            'description' => 'Confirm photographer package and contract.',
        ]);

        WeddingTask::create([
            'wedding_id' => $wedding->id,
            'task_name' => 'Finalize Guest List',
            'due_date' => now()->addDays(7)->toDateString(),
            'priority' => 'medium',
            'status' => 'in_progress',
            'description' => 'Follow up with pending guests.',
        ]);

        WeddingTask::create([
            'wedding_id' => $wedding->id,
            'task_name' => 'Book Venue',
            'due_date' => now()->subDays(3)->toDateString(),
            'priority' => 'high',
            'status' => 'pending',
            'description' => 'Venue booking task is overdue.',
        ]);

        WeddingTask::create([
            'wedding_id' => $wedding->id,
            'task_name' => 'Send Invitations',
            'due_date' => now()->subDays(2)->toDateString(),
            'priority' => 'medium',
            'status' => 'completed',
            'description' => 'Invitations already sent.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | WEDDING TIMELINE
        |--------------------------------------------------------------------------
        */

        WeddingTimelineItem::create([
            'wedding_id' => $wedding->id,
            'title' => 'Final Dress Fitting',
            'event_date' => now()->addDays(5)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:30',
            'location' => 'Bridal Studio',
            'category' => 'appointment',
            'status' => 'planned',
            'priority' => 'medium',
            'description' => 'Final fitting and adjustments.',
        ]);

        WeddingTimelineItem::create([
            'wedding_id' => $wedding->id,
            'title' => 'Meet Catering Vendor',
            'event_date' => now()->addDays(8)->toDateString(),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'location' => 'Wedding Venue',
            'category' => 'meeting',
            'status' => 'in_progress',
            'priority' => 'high',
            'description' => 'Finalize menu and guest headcount.',
        ]);

        WeddingTimelineItem::create([
            'wedding_id' => $wedding->id,
            'title' => 'Wedding Day Ceremony',
            'event_date' => $wedding->wedding_date,
            'start_time' => '15:00',
            'end_time' => '16:00',
            'location' => 'Garden Pavilion',
            'category' => 'ceremony',
            'status' => 'planned',
            'priority' => 'high',
            'description' => 'Wedding ceremony.',
        ]);

        WeddingTimelineItem::create([
            'wedding_id' => $wedding->id,
            'title' => 'Wedding Reception',
            'event_date' => $wedding->wedding_date,
            'start_time' => '18:00',
            'end_time' => '21:00',
            'location' => 'Garden Pavilion',
            'category' => 'reception',
            'status' => 'planned',
            'priority' => 'high',
            'description' => 'Wedding reception.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DONE
        |--------------------------------------------------------------------------
        */

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('TEST DATA CREATED SUCCESSFULLY');
        $this->command->info('========================================');
        $this->command->info('Email: test@example.com');
        $this->command->info('Password: password');
        $this->command->info('========================================');
    }
}