<?php

namespace App\Support;

use App\Models\Tenant;

final class BooksMode
{
    public const BASIC = 'basic';

    public const ADVANCED = 'advanced';

    /**
     * Route-name prefixes allowed while the company is on Basic.
     * A trailing dot matches a whole family (e.g. invoices.show).
     */
    private const BASIC_PREFIXES = [
        'dashboard',
        'estimates.',
        'invoices.',
        'customer-statements.',
        'customers.',
        'products.',
        'bills.',
        'suppliers.',
        'settings.company',
        'settings.changelog',
        'settings.plan',
        'settings.invite-firm',
        'settings.2fa',
        'profile.',
        'subscription.',
        'logout',
        'password.',
        'verification.',
        'provisioning',
        'provisioning.',
        'onboarding.',
        'privacy.',
        'books-mode.',
    ];

    public static function normalize(?string $mode): string
    {
        return $mode === self::BASIC ? self::BASIC : self::ADVANCED;
    }

    public static function forTenant(?Tenant $tenant): string
    {
        if (! $tenant) {
            return self::ADVANCED;
        }

        return self::normalize(isset($tenant->books_mode) ? (string) $tenant->books_mode : null);
    }

    public static function isBasic(?Tenant $tenant): bool
    {
        return self::forTenant($tenant) === self::BASIC;
    }

    public static function routeMatchesBasicPrefix(?string $routeName): bool
    {
        if ($routeName === null || $routeName === '') {
            return true;
        }

        foreach (self::BASIC_PREFIXES as $prefix) {
            if ($prefix === $routeName) {
                return true;
            }
            if (str_ends_with($prefix, '.') && str_starts_with($routeName, $prefix)) {
                return true;
            }
            if (! str_ends_with($prefix, '.') && (
                $routeName === $prefix || str_starts_with($routeName, $prefix.'.')
            )) {
                return true;
            }
        }

        return false;
    }

    public static function allowsRoute(?Tenant $tenant, ?string $routeName): bool
    {
        if (! self::isBasic($tenant)) {
            return true;
        }

        return self::routeMatchesBasicPrefix($routeName);
    }

    /**
     * Whether a sidebar route name should stay visible for the given mode.
     */
    public static function allowsNavRoute(?string $booksMode, ?string $routeName): bool
    {
        if (self::normalize($booksMode) !== self::BASIC) {
            return true;
        }

        return self::routeMatchesBasicPrefix($routeName);
    }
}
