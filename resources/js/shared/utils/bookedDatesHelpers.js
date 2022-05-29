// Loop over the array and instantiate new date object passing in the string
// so it is displayable in v-calendar.
export function prepareUnavailableDatesForCalendar(datesArray) {
    datesArray.forEach(element => {
        element = new Date(element);
    });

    return datesArray;
}