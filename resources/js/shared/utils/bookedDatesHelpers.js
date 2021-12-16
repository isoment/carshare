export function prepareUnavailableDatesForCalendar(datesArray) {
    datesArray.forEach(element => {
        element.start = new Date(element.start);
        element.end = new Date(element.end);
    });

    return datesArray;
}