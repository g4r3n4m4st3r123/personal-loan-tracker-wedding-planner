<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingGuest;
use App\Models\WeddingSeating;
use App\Models\WeddingTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingSeatingController extends Controller
{
    /**
     * Display seating arrangement.
     */
    public function index(): View
    {
        $userId = auth()->id();

        $wedding = Wedding::where('user_id', $userId)->first();

        if (!$wedding) {
            return view('wedding.seating', [
                'wedding' => null,
                'tables' => collect(),
                'guests' => collect(),
                'assignedGuests' => collect(),
                'unassignedGuests' => collect(),
                'totalGuests' => 0,
                'assignedCount' => 0,
                'unassignedCount' => 0,
                'totalTables' => 0,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tables
        |--------------------------------------------------------------------------
        */

        $tables = $wedding->tables()
            ->with([
                'seatings.guest'
            ])
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Guests
        |--------------------------------------------------------------------------
        */

        $guests = $wedding->guests()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Assigned Guests
        |--------------------------------------------------------------------------
        */

        $assignedGuestIds = WeddingSeating::where(
            'wedding_id',
            $wedding->id
        )
            ->pluck('wedding_guest_id');

        $assignedGuests = $guests->whereIn(
            'id',
            $assignedGuestIds
        );

        /*
        |--------------------------------------------------------------------------
        | Unassigned Guests
        |--------------------------------------------------------------------------
        */

        $unassignedGuests = $guests->whereNotIn(
            'id',
            $assignedGuestIds
        );

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalGuests = $guests->count();

        $assignedCount = $assignedGuests->count();

        $unassignedCount = $unassignedGuests->count();

        $totalTables = $tables->count();

        return view('wedding.seating', compact(
            'wedding',
            'tables',
            'guests',
            'assignedGuests',
            'unassignedGuests',
            'totalGuests',
            'assignedCount',
            'unassignedCount',
            'totalTables'
        ));
    }


    /**
     * Store a new table.
     */
    public function storeTable(Request $request): RedirectResponse
    {
        $userId = auth()->id();

        $wedding = Wedding::where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'table_name' => [
                'required',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $wedding->tables()->create($validated);

        return redirect()
            ->route('wedding.seating')
            ->with(
                'success',
                'Wedding table added successfully.'
            );
    }


    /**
     * Update a table.
     */
    public function updateTable(
        Request $request,
        WeddingTable $table
    ): RedirectResponse {

        if ($table->wedding->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'table_name' => [
                'required',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Don't allow capacity below current occupancy
        |--------------------------------------------------------------------------
        */

        $currentGuests = $table->seatings()->count();

        if ($validated['capacity'] < $currentGuests) {

            return back()
                ->withErrors([
                    'capacity' =>
                        "This table currently has {$currentGuests} guest(s). " .
                        "Capacity cannot be lower than the current occupancy."
                ])
                ->withInput();
        }

        $table->update($validated);

        return redirect()
            ->route('wedding.seating')
            ->with(
                'success',
                'Wedding table updated successfully.'
            );
    }


    /**
     * Delete a table.
     */
    public function destroyTable(
        WeddingTable $table
    ): RedirectResponse {

        if ($table->wedding->user_id !== auth()->id()) {
            abort(403);
        }

        $table->delete();

        return redirect()
            ->route('wedding.seating')
            ->with(
                'success',
                'Wedding table deleted successfully.'
            );
    }


    /**
     * Assign guest to a table.
     */
    public function assignGuest(
        Request $request
    ): RedirectResponse {

        $userId = auth()->id();

        $wedding = Wedding::where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'wedding_guest_id' => [
                'required',
                'integer',
                'exists:wedding_guests,id',
            ],

            'wedding_table_id' => [
                'required',
                'integer',
                'exists:wedding_tables,id',
            ],
        ]);

        $guest = WeddingGuest::where(
            'wedding_id',
            $wedding->id
        )
            ->where(
                'id',
                $validated['wedding_guest_id']
            )
            ->firstOrFail();

        $table = WeddingTable::where(
            'wedding_id',
            $wedding->id
        )
            ->where(
                'id',
                $validated['wedding_table_id']
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Don't assign declined guests
        |--------------------------------------------------------------------------
        */

        if ($guest->rsvp_status === 'declined') {

            return back()
                ->withErrors([
                    'wedding_guest_id' =>
                        'Declined guests cannot be assigned to a seating table.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Capacity Check
        |--------------------------------------------------------------------------
        */

        $existingSeating = WeddingSeating::where(
            'wedding_guest_id',
            $guest->id
        )->first();

        if ($existingSeating) {

            if ($existingSeating->wedding_table_id === $table->id) {

                return back()
                    ->with(
                        'success',
                        'Guest is already assigned to this table.'
                    );
            }

            return back()
                ->withErrors([
                    'wedding_guest_id' =>
                        'This guest is already assigned to another table.'
                ]);
        }

        $occupied = $table->seatings()->count();

        if ($occupied >= $table->capacity) {

            return back()
                ->withErrors([
                    'wedding_table_id' =>
                        'This table is already full.'
                ]);
        }

        WeddingSeating::create([
            'wedding_id' => $wedding->id,
            'wedding_table_id' => $table->id,
            'wedding_guest_id' => $guest->id,
        ]);

        return redirect()
            ->route('wedding.seating')
            ->with(
                'success',
                'Guest assigned to table successfully.'
            );
    }


    /**
     * Remove guest from table.
     */
    public function removeGuest(
        WeddingSeating $seating
    ): RedirectResponse {

        if ($seating->wedding->user_id !== auth()->id()) {
            abort(403);
        }

        $seating->delete();

        return redirect()
            ->route('wedding.seating')
            ->with(
                'success',
                'Guest removed from table successfully.'
            );
    }
}