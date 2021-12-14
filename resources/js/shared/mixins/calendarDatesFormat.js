export default {
    methods: {
        // Loop over the array and instantiate new date object passing in the string
        // so it is displayable in v-calendar.
        prepareUnavailableDatesForCalendar(datesArray) {
            datesArray.forEach(element => {
                element.start = new Date(element.start);
                element.end = new Date(element.end);
            });

            return datesArray;
        }
    }
}