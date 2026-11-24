<?php

namespace App\Http\Controllers;

use App\Support\BooksMode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BooksModeController extends Controller
{
    /**
     * Flip the current company to Advanced so hidden screens appear.
     * Used from the Advanced explanation page when someone opens a
     * Basic-hidden URL.
     */
    public function enableAdvanced(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user || ! $user->canAdminCurrentTenant()) {
            return redirect()->route('dashboard')->with(
                'error',
                'Only a company admin can turn on Advanced mode.'
            );
        }

        $tenant = function_exists('tenant') ? tenant() : null;
        if (! $tenant) {
            return redirect()->route('dashboard');
        }

        $tenant->books_mode = BooksMode::ADVANCED;
        $tenant->save();

        $redirectTo = route('dashboard');
        $requested = $request->input('redirect');
        if (is_string($requested) && $requested !== '') {
            $appUrl = rtrim((string) config('app.url'), '/');
            if (str_starts_with($requested, '/') && ! str_starts_with($requested, '//')) {
                $redirectTo = $requested;
            } elseif ($appUrl !== '' && str_starts_with($requested, $appUrl.'/')) {
                $redirectTo = $requested;
            }
        }

        return redirect()->to($redirectTo)->with(
            'success',
            'Advanced mode is on. The full menu is available.'
        );
    }
}
