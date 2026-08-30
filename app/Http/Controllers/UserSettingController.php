<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserSettingController extends Controller
{
    /**
     * Update application preferences.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currency' => [
                'required',
                Rule::in([
                    'PHP',
                    'USD',
                    'EUR',
                    'GBP',
                    'JPY',
                    'SGD',
                ]),
            ],

            'currency_symbol' => [
                'required',
                'string',
                'max:10',
            ],

            'date_format' => [
                'required',
                Rule::in([
                    'M d, Y',
                    'd M Y',
                    'Y-m-d',
                    'F d, Y',
                ]),
            ],

            'week_starts_on' => [
                'required',
                Rule::in([
                    'monday',
                    'sunday',
                ]),
            ],

            'dashboard_view' => [
                'required',
                Rule::in([
                    'overview',
                    'finance',
                    'wedding',
                ]),
            ],

            'show_wedding_dashboard' => [
                'nullable',
                'boolean',
            ],

            'show_finance_dashboard' => [
                'nullable',
                'boolean',
            ],
        ]);


        UserSetting::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'currency' =>
                    $validated['currency'],

                'currency_symbol' =>
                    $validated['currency_symbol'],

                'date_format' =>
                    $validated['date_format'],

                'week_starts_on' =>
                    $validated['week_starts_on'],

                'dashboard_view' =>
                    $validated['dashboard_view'],

                'show_wedding_dashboard' =>
                    $request->boolean(
                        'show_wedding_dashboard'
                    ),

                'show_finance_dashboard' =>
                    $request->boolean(
                        'show_finance_dashboard'
                    ),
            ]
        );


        return redirect()
            ->route('profile.edit')
            ->with(
                'settings_success',
                'Application preferences saved successfully.'
            );
    }
}