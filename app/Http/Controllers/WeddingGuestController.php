<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingGuest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingGuestController extends Controller
{
    /**
     * Display wedding guests.
     */
    public function index(Request $request): View
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $query = $wedding->guests();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | RSVP Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('rsvp_status')) {
            $query->where(
                'rsvp_status',
                $request->rsvp_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Guest Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('guest_type')) {
            $query->where(
                'guest_type',
                $request->guest_type
            );
        }

        $guests = $query
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $allGuests = $wedding->guests()->get();

        $totalGuests = $allGuests->count();

        $attendingGuests = $allGuests
            ->where('rsvp_status', 'attending')
            ->count();

        $pendingGuests = $allGuests
            ->where('rsvp_status', 'pending')
            ->count();

        $declinedGuests = $allGuests
            ->where('rsvp_status', 'declined')
            ->count();

        $plusOneCount = $allGuests
            ->where('plus_one', true)
            ->count();

        $estimatedHeadcount = $attendingGuests + $plusOneCount;


        return view(
            'wedding.guests.index',
            compact(
                'wedding',
                'guests',
                'totalGuests',
                'attendingGuests',
                'pendingGuests',
                'declinedGuests',
                'plusOneCount',
                'estimatedHeadcount'
            )
        );
    }


    /**
     * Store a new guest.
     */
    public function store(Request $request): RedirectResponse
    {
        $wedding = Wedding::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'guest_type' => [
                'required',
                'in:bride_side,groom_side,family,friend,colleague,other',
            ],

            'rsvp_status' => [
                'required',
                'in:pending,attending,declined',
            ],

            'plus_one' => [
                'nullable',
                'boolean',
            ],

            'meal_preference' => [
                'nullable',
                'string',
                'max:100',
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

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $validated['plus_one'] =
            $request->boolean('plus_one');

        $wedding->guests()->create(
            $validated
        );

        return redirect()
            ->route('wedding.guests')
            ->with(
                'success',
                'Guest added successfully.'
            );
    }


    /**
     * Update a guest.
     */
    public function update(
        Request $request,
        WeddingGuest $guest
    ): RedirectResponse {

        if (
            $guest->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'guest_type' => [
                'required',
                'in:bride_side,groom_side,family,friend,colleague,other',
            ],

            'rsvp_status' => [
                'required',
                'in:pending,attending,declined',
            ],

            'plus_one' => [
                'nullable',
                'boolean',
            ],

            'meal_preference' => [
                'nullable',
                'string',
                'max:100',
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

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $validated['plus_one'] =
            $request->boolean('plus_one');

        $guest->update(
            $validated
        );

        return redirect()
            ->route('wedding.guests')
            ->with(
                'success',
                'Guest updated successfully.'
            );
    }


    /**
     * Delete a guest.
     */
    public function destroy(
        WeddingGuest $guest
    ): RedirectResponse {

        if (
            $guest->wedding->user_id
            !== auth()->id()
        ) {
            abort(403);
        }

        $guest->delete();

        return redirect()
            ->route('wedding.guests')
            ->with(
                'success',
                'Guest deleted successfully.'
            );
    }
}