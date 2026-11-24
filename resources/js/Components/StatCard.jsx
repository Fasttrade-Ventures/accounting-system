export default function StatCard({ label, value, hint }) {
    return (
        <div className="bg-surface rounded-2xl border border-border-warm p-4 sm:p-6 min-w-0">
            <p className="text-xs font-medium text-ink-muted">{label}</p>
            <p className="mt-2 text-xl sm:text-2xl font-semibold text-ink tabular-nums tracking-tight">{value}</p>
            {hint ? <p className="mt-1 text-xs text-ink-muted">{hint}</p> : null}
        </div>
    );
}

export const salesPrimaryButton = 'inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-terracotta hover:bg-terracotta-dark';
