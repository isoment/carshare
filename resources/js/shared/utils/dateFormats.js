import moment from "moment";

export function dateFormatMonthYear(date) {
    return moment(date).format('MMMM YYYY');
}