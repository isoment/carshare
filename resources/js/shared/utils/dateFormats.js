import moment from "moment";

export function dateFormatMonthYear(date) {
    return moment(date).format('MMMM YYYY');
}

export function dateFormatMonthDayYear(date) {
    return moment(date).format('MMMM Do YYYY');
}

// MM/DD/YYYY Format
export function monthDayYearNumbericSlash(date) {
    return moment(date).format('L');
}

// YYYY-MM-DD Format
export function yearMonthDayNumericHyphen(date) {
    return moment(date).format('YYYY-MM-DD');
}

// Jan 12th 2020
export function humanReadableDate(date) {
    return moment(date).format('MMM Do YYYY');
}