<template>
    <div class="w-3/4 md:w-full">

        <date-picker v-model="range" 
                    color="purple" 
                    is-range
                    :min-date="new Date()">
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
                <span class="ml-1" v-text="availabilityError"></span>
            </h5>
        </div>

        <div v-if="available" class="mt-4 -mb-2">
            <h5 class="text-green-400 text-xs flex items-center">
                <span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span> 
                <span class="ml-1">Vehicle available on these dates!</span>
            </h5>
        </div>

    </div>
</template>

<script>
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import { dateTypeCheck, dateSetterStart, dateSetterEnd, dateValid } from './../shared/utils/dateHelpers';
    import { mapState } from 'vuex';

    export default {
        components: {
            Calendar,
            DatePicker
        },

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
                range: {
                    start: null,
                    end: null,
                },
                previousDates: null,
                status: null,
                availabilityError: null
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
                // Call the action to change dates in local storage.
                this.$store.dispatch('setSearchDates', {
                    start: dateTypeCheck(this.range.start),
                    end: dateTypeCheck(this.range.end)
                });

                this.checkAvailability();
            },

            async checkAvailability() {
                try {
                    this.status = (await axios.get(`/api/vehicle-availability/${this.$route.params.id}`, {
                        params: {
                            from: this.$store.state.searchDates.start,
                            to: this.$store.state.searchDates.end
                        }
                    })).status

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

                    this.availabilityError = error.response.data;

                    this.$emit('renderPrice');

                    this.$emit('availability', this.available);
                }
            },
        },

        created() {
            // Set the date range
            this.range.start = dateSetterStart(this.$store.state.searchDates.start);
            this.range.end = dateSetterEnd(this.$store.state.searchDates.end);

            // Previous dates
            this.previousDates = this.range;

            this.checkAvailability();
        }
    }
</script>