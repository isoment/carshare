/*
    Check if the date is a string or date object.
    If its a string already return it else if its a
    Date object convert to string MM/DD/YYYY format
*/
export function dateTypeCheck(date) {
    if (typeof date === 'string') {
        return date;
    } else {
        return date.toLocaleDateString();
    }
}