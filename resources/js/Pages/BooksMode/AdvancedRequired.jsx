import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, usePage } from '@inertiajs/react';

export default function AdvancedRequired({ auth, attempted_url, can_enable = false }) {
    const { flash } = usePage().props;

    const enableAdvanced = () => {
        router.post(route('books-mode.enable-advanced'), {
            redirect: attempted_url || route('dashboard'),
        });
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Advanced mode" />

            <div className="max-w-xl mx-auto py-12 px-4">
                <div className="rounded-2xl border border-border-warm bg-surface shadow-sm p-8 text-center space-y-4">
                    <p className="text-eyebrow font-semibold uppercase text-terracotta">Advanced</p>
                    <h1 className="font-display text-2xl font-medium text-ink tracking-tight">
                        This screen is part of Advanced
                    </h1>
                    <p className="text-sm text-ink-muted leading-relaxed">
                        Your company is on Basic, which keeps the menu short for quotes, invoices, and bills.
                        Turning on Advanced shows the full set of books screens. Nothing already saved is removed.
                    </p>
                    {flash?.error && (
                        <p className="text-sm text-terracotta">{flash.error}</p>
                    )}
                    <div className="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        {can_enable ? (
                            <button
                                type="button"
                                onClick={enableAdvanced}
                                className="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-terracotta text-white text-sm font-semibold hover:bg-terracotta/90 transition-colors"
                            >
                                Turn on Advanced
                            </button>
                        ) : (
                            <p className="text-sm text-ink-muted">
                                Ask a company admin to turn on Advanced in Company settings.
                            </p>
                        )}
                        <Link
                            href={route('dashboard')}
                            className="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-border-warm text-sm font-semibold text-ink hover:bg-cream transition-colors"
                        >
                            Back to dashboard
                        </Link>
                    </div>
                    <p className="text-xs text-ink-muted pt-2">
                        You can also switch modes anytime under Company settings.
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
