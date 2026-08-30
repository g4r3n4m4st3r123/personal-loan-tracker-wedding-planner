<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingVendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingVendorController extends Controller
{
    /**
     * Display wedding vendors.
     */
    public function index(Request $request): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $query = $wedding->vendors();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'vendor_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'service_type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'contact_person',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'phone',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'email',
                        'like',
                        "%{$search}%"
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Service Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('service_type')) {

            $query->where(
                'service_type',
                $request->service_type
            );
        }


        $vendors = $query
            ->orderBy('service_date')
            ->orderBy('vendor_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | All Vendors For Statistics
        |--------------------------------------------------------------------------
        */

        $allVendors = $wedding->vendors()->get();


        $totalVendors = $allVendors->count();


        $totalAgreedAmount = (float) $allVendors->sum(
            'agreed_amount'
        );


        $totalAmountPaid = (float) $allVendors->sum(
            'amount_paid'
        );


        $totalOutstanding = max(
            0,
            $totalAgreedAmount
            - $totalAmountPaid
        );


        $fullyPaidVendors = $allVendors
            ->filter(
                fn ($vendor) =>
                    (float) $vendor->agreed_amount > 0
                    &&
                    (float) $vendor->amount_paid
                    >= (float) $vendor->agreed_amount
            )
            ->count();


        $upcomingVendors = $allVendors
            ->filter(function ($vendor) {

                if (!$vendor->service_date) {
                    return false;
                }

                return $vendor->service_date
                    ->greaterThanOrEqualTo(
                        now()->startOfDay()
                    );
            })
            ->sortBy('service_date')
            ->take(5);


        return view(
            'wedding.vendors.index',
            compact(
                'wedding',
                'vendors',
                'totalVendors',
                'totalAgreedAmount',
                'totalAmountPaid',
                'totalOutstanding',
                'fullyPaidVendors',
                'upcomingVendors'
            )
        );
    }


    /**
     * Store a new vendor.
     */
    public function store(Request $request): RedirectResponse
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        $validated = $request->validate([
            'vendor_name' => [
                'required',
                'string',
                'max:255',
            ],

            'service_type' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'agreed_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'amount_paid' => [
                'required',
                'numeric',
                'min:0',
                'lte:agreed_amount',
            ],

            'payment_status' => [
                'required',
                'in:unpaid,partial,paid',
            ],

            'booking_date' => [
                'nullable',
                'date',
            ],

            'service_date' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Automatically Correct Payment Status
        |--------------------------------------------------------------------------
        */

        $agreedAmount = (float) $validated['agreed_amount'];

        $amountPaid = (float) $validated['amount_paid'];


        if ($amountPaid <= 0) {

            $paymentStatus = 'unpaid';

        } elseif ($agreedAmount > 0 && $amountPaid >= $agreedAmount) {

            $paymentStatus = 'paid';

        } else {

            $paymentStatus = 'partial';
        }


        $validated['payment_status'] =
            $paymentStatus;


        $wedding->vendors()->create(
            $validated
        );


        return redirect()
            ->route('wedding.vendors')
            ->with(
                'success',
                'Wedding vendor added successfully.'
            );
    }


    /**
     * Update a vendor.
     */
    public function update(
        Request $request,
        WeddingVendor $vendor
    ): RedirectResponse {

        if (
            $vendor->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }


        $validated = $request->validate([
            'vendor_name' => [
                'required',
                'string',
                'max:255',
            ],

            'service_type' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'agreed_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'amount_paid' => [
                'required',
                'numeric',
                'min:0',
                'lte:agreed_amount',
            ],

            'payment_status' => [
                'required',
                'in:unpaid,partial,paid',
            ],

            'booking_date' => [
                'nullable',
                'date',
            ],

            'service_date' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Automatically Correct Payment Status
        |--------------------------------------------------------------------------
        */

        $agreedAmount =
            (float) $validated['agreed_amount'];

        $amountPaid =
            (float) $validated['amount_paid'];


        if ($amountPaid <= 0) {

            $paymentStatus = 'unpaid';

        } elseif (
            $agreedAmount > 0
            && $amountPaid >= $agreedAmount
        ) {

            $paymentStatus = 'paid';

        } else {

            $paymentStatus = 'partial';
        }


        $validated['payment_status'] =
            $paymentStatus;


        $vendor->update(
            $validated
        );


        return redirect()
            ->route('wedding.vendors')
            ->with(
                'success',
                'Wedding vendor updated successfully.'
            );
    }


    /**
     * Delete a vendor.
     */
    public function destroy(
        WeddingVendor $vendor
    ): RedirectResponse {

        if (
            $vendor->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }


        $vendor->delete();


        return redirect()
            ->route('wedding.vendors')
            ->with(
                'success',
                'Wedding vendor deleted successfully.'
            );
    }
}