const PLAIN_TAX_LABELS = {
    ES: 'No tax',
    'SR-8': 'Service tax 8%',
    'ST-10': 'Sales tax 10%',
};

export function visibleTaxCodes(codes = []) {
    return codes.filter((code) => code.code !== 'ZRL');
}

export function taxChoiceLabel(code) {
    if (!code) {
        return 'No tax';
    }
    if (PLAIN_TAX_LABELS[code.code]) {
        return PLAIN_TAX_LABELS[code.code];
    }
    const rate = Number(code.rate);
    if (code.name && !Number.isNaN(rate)) {
        return `${code.name} (${rate}%)`;
    }
    return code.name || `${rate}%`;
}
