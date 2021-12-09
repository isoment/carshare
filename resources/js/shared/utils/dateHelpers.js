import moment from "moment";

/**
 *  Check if the date is a string or date object.
 *  If its a string already return it else if its a
 *  date object convert to string MM/DD/YYYY format
 * 
 *  @param {string|Date} date 
 *  @returns {string}
 */
export function dateTypeCheck(date) {
    if (typeof date === 'string') {
        return date;
    } else {
        return date.toLocaleDateString();
    }
}


/**
 *  Check if a date is valid
 * 
 *  @param {string} date 
 *  @returns {boolean}
 */
export function dateValid(date) {
    return moment(date).isValid();
}