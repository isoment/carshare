<template>
    <div class="w-3/4 md:w-full">
        <div v-if="unavailableDates">

            <date-picker v-model="range" 
                        color="purple" 
                        is-range
                        :min-date="minDate"
                        :max-date="maxDate"
                        :disabled-dates='unavailableDates'>
                <template v-slot="{ inputValue, inputEvents }">
                    <div class="flex flex-col">
                        <label for="start" class="font-bold text-xs tracking-wider">Trip start</label>
                        <input type="text" class="border border-gray-300 mt-1 py-1 px-2 focus:outline-none text-sm"
                            :value="inputValue.start"
                            v-on="inputEvents.start"
                            readonly>
                    </div>
                    <div class="flex flex-col mt-3">
                        <label for="start" class="font-bold text-xs tracking-wider">Trip end</label>
                        <input type="text" class="border border-gray-300 mt-1 py-1 px-2 focus:outline-none text-sm"
                            :value="inputValue.end"
                            v-on="inputEvents.end"
                            readonly>
                    </div>
                </template>
            </date-picker>

            <div v-if="notAvailable" class="mt-4 -mb-2">
                <h5 class="text-red-400 text-xs flex items-center">
                    <span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span> 
                    <span class="ml-1" v-text="responseMessage"></span>
                </h5>
            </div>

            <div v-if="available" class="mt-4 -mb-2">
                <h5 class="text-green-400 text-xs flex items-center">
                    <span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span> 
                    <span class="ml-1" v-text="responseMessage"></span>
                </h5>
            </div>

        </div>

        <div v-else>
            <div class="text-center mt-8">
                <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import vehicleSearchDatesComputed from './../shared/mixins/vehicleSearchDatesComputed';
    import { prepareUnavailableDatesForCalendar } from './../shared/utils/bookedDatesHelpers';
    import calendarMinMaxDate from './../shared/mixins/calendarMinMaxDate';

    export default {
        components: {
            Calendar,
            DatePicker
        },

        mixins: [vehicleSearchDatesComputed, calendarMinMaxDate],

        watch: {
            // Set a watcher to trigger the changedDate method when 
            // the date is changed.
            range: {
                handler: function () {
                    this.changedDate();
                },
                // deep: true
            }
        },

        data() {
            return {
                previousDates: null,
                unavailableDates: null,
                status: null,
                responseMessage: null
            }
        },

        computed: {
            ...mapState({
                isLoggedIn: state => state.isLoggedIn
            }),

            notAvailable() {
                return this.status === 404;
            },

            available() {
                return this.status === 200;
            },
        },

        methods: {
            changedDate() {
                this.checkAvailability();
            },

            async checkAvailability() {
                // Change the api route based on if the user is authenticated
                const authorization = this.isLoggedIn ? 'auth' : 'guest';

                try {
                    let response = (await axios.get(
                        `/api/vehicle-availability-${authorization}/${this.$route.params.id}`, 
                        {
                            params: {
                                from: this.$store.state.searchDates.start,
                                to: this.$store.state.searchDates.end
                            }
                        }));

                    this.status = response.status;

                    this.responseMessage = response.data.message;

                    this.unavailableDates = prepareUnavailableDatesForCalendar(
                        response.data.unavailableDates
                    );

                    this.$emit('renderPrice');

                    this.$emit('availability', this.available);
                } catch (error) {
                    if (error.response && error.response.status && error.response.status === 422) {
                        this.range = this.previousDates;

                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'The given dates are invalid!'
                        });
                    }

                    this.status = error.response.status;

                    this.responseMessage = error.response.data.message;

                    this.unavailableDates = prepareUnavailableDatesForCalendar(
                        error.response.data.unavailableDates
                    );

                    this.$emit('renderPrice');

                    this.$emit('availability', this.available);
                }
            },
        },

        created() {
            // Check and set search dates
            this.$store.dispatch('checkSearchDates');

            // Previous dates
            this.previousDates = this.range;

            this.checkAvailability();
        }
    }
</script>