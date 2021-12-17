import moment from 'moment';

export default {
    computed: {
        minDate() {
            return moment().toDate();
        },

        maxDate() {
            return moment().add(1, 'year').toDate();
        }
    }
}