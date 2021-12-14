import { mapState } from 'vuex';
import calendarDatesFormat from './../mixins/calendarDatesFormat';

export default {
    data() {
        return {
            userBookedDates: null
        }
    },

    mixins: [calendarDatesFormat],

    computed: {
        ...mapState({
            isLoggedIn: state => state.isLoggedIn
        }),
    },

    methods: {
        /**
         *  Need to make a request to the api endpoint and get a listing of the booked
         *  dates which we set to the state above for use in v-calendar.
         */
        async setUserBookedDates() {
            if (this.isLoggedIn) {
                try {
                    let response = await axios.get('/api/users-booking-dates');

                    this.userBookedDates = this.prepareUnavailableDatesForCalendar(
                        response.data.unavailableDates
                    );
                } catch (error) {
                    this.userBookedDates = [];
                }  
            }
        }
    }
}