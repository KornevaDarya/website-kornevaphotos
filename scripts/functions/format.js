export function number_format(amount, decimalCount, decimal, thousands) {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    var negativeSign = amount < 0 ? "-" : "",
        i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString(),
        j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
}
export function int_format(int) {
    var true_int = parseInt(int);
    return number_format(true_int, 0, ",", " ");
}

export function float_format(float) {
    var true_float = parseFloat(float);
    return number_format(true_float, 2, ",", " ");
}

export function currency_format(float) {
    var true_float = parseFloat(float);
    return number_format(true_float, 0, ",", " ") + " ₽";
}