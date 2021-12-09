import { dateTypeCheck } from './../utils/dateHelpers';

export default {
    computed: {
        // Use a computed getter and setter to interact with the vuex
        // store directly.
        range: {
            set(range) {
                this.$store.dispatch('setSearchDates', {
                    start: dateTypeCheck(range.start),
                    end: dateTypeCheck(range.end)
                });
            },

            get() {
                return this.$store.state.searchDates;
            }
        }
    },
}