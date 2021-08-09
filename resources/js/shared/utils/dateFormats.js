import moment from "moment";

export function dateFormatMonthYear(date) {
    return moment(date).format('MMMM YYYY');
}

export function dateFormatMonthDayYear(date) {
    return moment(date).format('MMMM Do YYYY');
}

// MM/DD/YYYY Format
export function dateFormatForPhpValidation(date) {
    return moment(date).format('L');
}