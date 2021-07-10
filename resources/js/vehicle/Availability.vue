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
                           v-on="inputEvents.start">
                </div>
                <div class="flex flex-col mt-3">
                    <label for="start" class="font-bold text-xs tracking-wider">Trip end</label>
                    <input type="text" class="border border-gray-300 mt-1 py-1 px-2 focus:outline-none text-sm"
                           :value="inputValue.end"
                           v-on="inputEvents.end">
                </div>
            </template>
        </date-picker>

        <div v-if="notAvailable" class="mt-4 -mb-2">
            <h5 class="text-red-500 text-xs font-bold">Vehicle unavailable on these dates</h5>
        </div>

        <div class="mt-6">
            <button class="bg-purple-500 text-white font-bold py-2 w-full text-sm 
                            tracking-widest hover:bg-purple-400 transition-all duration-200">Continue</button>
        </div>

    </div>
</template>

<script>
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import { dateTypeCheck, dateSetterStart, dateSetterEnd } from './../shared/utils/dateHelpers';
    import validationErrors from './../shared/mixins/validationErrors';

    export default {
        components: {
            Calendar,
            DatePicker
        },

        mixins: [validationErrors],

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
                status: null,
            }
        },

        computed: {
            notAvailable() {
                return this.status === 404;
            }
        },

        methods: {
            changedDate() {
                // Call the action to set local storage.
                this.$store.dispatch('setSearchDates', {
                    start: dateTypeCheck(this.range.start),
                    end: dateTypeCheck(this.range.end)
                });

                this.checkAvailability();
            },

            async checkAvailability() {
                this.validationErrors = null;

                try {
                    this.status = (await axios.
                        get(`/api/vehicle-availability/${this.$route.params.id}?from=${this.$store.state.searchDates.start}&to=${this.$store.state.searchDates.end}`))
                        .status;
                } catch (error) {
                    if (error.response && error.response.status && error.response.status === 422) {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'The given dates are invalid!'
                        });

                        this.errors = error.response.data.errors;
                    }

                    this.status = error.response.status;
                }
            }
        },

        created() {
            // Set the date range
            this.range.start = dateSetterStart(this.$store.state.searchDates.start);
            this.range.end = dateSetterEnd(this.$store.state.searchDates.end);
        }
    }
</script>