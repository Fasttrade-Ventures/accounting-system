const DONE = new Set(['paid', 'accepted', 'converted', 'posted', 'active', 'delivered', 'invoiced', 'applied', 'settled']);
const ATTENTION = new Set(['unpaid', 'rejected', 'expired', 'forfeited', 'overdue']);

export function statusClass(status) {
    const key = String(status || '').toLowerCase();
    if (ATTENTION.has(key)) return 'bg-terracotta/10 text-terracotta';
    if (DONE.has(key)) return 'bg-forest/10 text-forest';
    return 'bg-surface-alt text-ink-muted';
}

export function statusPill(status) {
    return `inline-flex px-2 py-0.5 rounded-md text-[10px] font-semibold ${statusClass(status)}`;
}
