export default {
    computed: {
        bookedDatesAttributes() {
            let bookedDates = this.$store.state.bookedDates;

            let labelAttributes = [
                {
                    dates: bookedDates,
                    dot: {color: 'red'},
                    popover: {
                        label: 'Date already booked',
                    },
                }
            ]

            return bookedDates ? labelAttributes : [];
        }
    }
}