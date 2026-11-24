<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\BooksMode;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureBooksMode
{
    /**
     * Route-name prefixes that are never gated by books mode
     * (practice console, platform admin, firm invite flow).
     */
    private const EXEMPT_PREFIXES = [
        'practice.',
        'admin.',
        'firm.',
        'books-mode.',
        'impersonate.',
        'sanctum.',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = optional($request->route())->getName();

        if ($this->isExempt($routeName)) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        if ($user->role_name === 'super-admin' || (method_exists($user, 'hasRole') && $user->hasRole('super-admin'))) {
            return $next($request);
        }

        $tenant = $this->resolveTenant($request, $user);
        if (! $tenant || ! BooksMode::isBasic($tenant)) {
            return $next($request);
        }

        if (BooksMode::allowsRoute($tenant, $routeName)) {
            return $next($request);
        }

        return Inertia::render('BooksMode/AdvancedRequired', [
            'attempted_route' => $routeName,
            'attempted_url' => $request->fullUrl(),
            'can_enable' => method_exists($user, 'canAdminCurrentTenant')
                ? (bool) $user->canAdminCurrentTenant()
                : false,
        ])->toResponse($request)->setStatusCode(200);
    }

    private function isExempt(?string $routeName): bool
    {
        if ($routeName === null || $routeName === '') {
            return true;
        }

        foreach (self::EXEMPT_PREFIXES as $prefix) {
            if (str_starts_with($routeName, $prefix) || $routeName === rtrim($prefix, '.')) {
                return true;
            }
        }

        return false;
    }

    private function resolveTenant(Request $request, $user): ?Tenant
    {
        if (function_exists('tenant') && tenant()) {
            return tenant();
        }

        if (method_exists($user, 'isFirmUser') && $user->isFirmUser()) {
            $actingTenantId = $request->session()->get('acting_tenant_id');
            if ($actingTenantId) {
                return Tenant::find($actingTenantId);
            }

            return null;
        }

        if ($user->tenant_id) {
            return Tenant::find($user->tenant_id);
        }

        return null;
    }
}
