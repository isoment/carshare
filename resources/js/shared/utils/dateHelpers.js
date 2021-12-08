import moment from "moment";

/**
 * Check if the date is a string or date object.
 * If its a string already return it else if its a
 * Date object convert to string MM/DD/YYYY format
 * 
 * @param {string|Date} date 
 * @returns {string}
 */
export function dateTypeCheck(date) {
    if (typeof date === 'string') {
        return date;
    } else {
        return date.toLocaleDateString();
    }
}

/*
    Set the start date if its stored in local storage, if not
    create a new range starting today and ending tomorrow.
*/
export function dateSetterStart(start) {
    return start ? 
        start :
        new Date().toLocaleDateString();
}

/*
    Set the end date if its stored in local storage, if not
    create a new range starting today and ending tomorrow
*/
export function dateSetterEnd(end) {
    let today = new Date();

    return end ?
        end :
        new Date(today.getTime() + (24 * 60 * 60 * 1000)).toLocaleDateString();
}

/*
    Check if a date is valid
*/
export function dateValid(date) {
    let toCheck = moment(date);
    return toCheck.isValid();
}