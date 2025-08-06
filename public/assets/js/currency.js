// 1. Global map of all currency configs (populated after page load via AJAX)
let currencyConfigs = {}; // { USD: { symbol: "$", ... }, INR: { symbol: "₹", ... } }

/**
 * Format a price using global currency config
 * @param {string} currencyCode  - Currency code (e.g., "USD", "INR")
 * @param {number} amount        - Amount to format
 * @returns {string}
 */
function formatPrice(currencyCode, amount) {
    const config = currencyConfigs[currencyCode];

    if (!config) return currencyCode + " " + amount;

    const {
        symbol = currencyCode,
        decimal = 2,
        decimal_separator = ".",
        group_separator = ",",
        currency_position = "Left",
    } = config;

    if (isNaN(amount)) return symbol + " --";

    let parts = Number(amount).toFixed(decimal).split(".");

    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, group_separator);

    const formatted = parts.join(decimal_separator);

    return currency_position === "Left"
        ? symbol + formatted
        : formatted + symbol;
}

$(document).ready(function () {
    $.ajax({
        url: `${appUrl}/ajax/currencies`,
        method: "GET",
        success: function (response) {
            currencyConfigs = response.data || {};
        },
        error: function () {
            console.error("Failed to load currency configs");
        },
    });
});
