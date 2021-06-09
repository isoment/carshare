<template>
    <div>

        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
            
            <div class="pt-4 pb-2 mb-2 border-b border-gray-100">
                <div class="flex">
                    <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2
                                text-gray-700 focus:outline-none hover:bg-gray-100 hover:border-gray-100
                                transition-all duration-300"
                            @click="toggleDatesMenu">
                        <i class="far fa-calendar-alt text-md"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Change dates
                        </div>
                    </button>
                    <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2 ml-2
                                text-gray-700 focus:outline-none hover:bg-gray-100 hover:border-gray-100
                                transition-all duration-300">
                        <i class="fas fa-sliders-h"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Filters
                        </div>
                    </button>
                </div>

                <transition name="slide-fade">
                    <div v-show="datesMenu" class="w-full md:w-1/2 my-2">
                        <date-picker v-model="range" 
                                    color="purple" 
                                    is-range
                                    :min-date="new Date()">
                            <template v-slot="{ inputValue, inputEvents }">
                                <div class="flex items-center">
                                    <div class="flex items-center border-b border-gray-300">
                                        <div>
                                            <label for="from" class="text-purple-500 font-bold text-sm">From</label>
                                        </div>
                                        <input type="text" name="from" class="ml-2 main-vehicle-date-input focus:outline-none"
                                            :value="inputValue.start"
                                            v-on="inputEvents.start">
                                    </div>
                                    <div class="flex items-center border-b border-gray-300 ml-3">
                                        <div>
                                            <label for="until" class="text-purple-500 font-bold text-sm">Until</label>
                                        </div>
                                        <input type="text" name="until" class="ml-2 main-vehicle-date-input focus:outline-none"
                                            :value="inputValue.end"
                                            v-on="inputEvents.end">
                                    </div>
                                    <div>
                                        <button class="bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                                        px-2 py-1 focus:outline-none rounded-lg ml-3"
                                                @click="updateDates">
                                            <i class="fas fa-search text-white text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </date-picker>
                    </div>
                </transition>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-8 shadow-lg border border-gray-50 rounded-lg"
                     v-for="vehicle in vehicles" 
                     :key="vehicle.id">
                    <div>
                        <h3 class="font-bold font-boldnosans text-xl">{{ vehicle.vehicle_make }} {{ vehicle.model }}</h3>
                        <h6 class="font-light text-sm">{{ vehicle.year }}</h6>
                        <h6 class="font-light text-sm">Vehicle ID {{ vehicle.id }}</h6>
                    </div>
                    <div class="text-right font-bold text-sm text-purple-500 mt-6">
                        ${{ vehicle.price_day }} / Day
                    </div>
                </div>
            </div>
            <div class="text-center mt-8"
                 v-if="loading">
                  <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
            </div>
            <div v-if="endOfResults" class="my-4">
                No More Vehicles
            </div>
            <div v-observe-visibility="handleScrolledToBottom"
                 v-if="vehicles.length">
            </div>
        </div>

    </div>
</template>

<script>
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import { dateTypeCheck, dateSetterStart, dateSetterEnd } from './../shared/utils/dateHelpers';

    export default {
        components: {
            Calendar,
            DatePicker
        },

        data() {
            return {
                loading: false,
                vehicles: [],
                page: 1,
                lastPage: 1,
                endOfResults: false,
                datesMenu: false,
                range: {
                    start: null,
                    end: null
                },
            }
        },

        methods: {
            toggleDatesMenu() {
                this.datesMenu = !this.datesMenu;
            },

            updateDates() {
                // Call the action to set local storage
                this.$store.dispatch('setSearchDates', {
                    start: dateTypeCheck(this.range.start),
                    end: dateTypeCheck(this.range.end)
                });

                // Get and parse dates from local storage
                let dates = localStorage.getItem('searchDates');
                let start = JSON.parse(dates).start;
                let end = JSON.parse(dates).end;

                // Redirect to same page to update query string
                // Don't log the error router throws when navigating to
                // same page if the query string isn't updated.
                this.$router.push({
                    name: 'main-vehicle',
                    query: {
                        start: start,
                        end: end
                    }
                }).catch(error => {
                    if (
                        error.name !== 'NavigationDuplicated' &&
                        !error.message.includes('Avoided redundant navigation to current location')
                    ) {
                        console.error(error);
                    }
                });

                // Clear the vehicles array
                this.vehicles = [];

                // Reset page to first page
                this.page = 1;

                this.fetchVehicles();
            },

            async fetchVehicles() {
                this.loading = true;

                let vehicles = await axios.get(
                    `/api/vehicles-index?page=${this.page}&from=${this.$store.state.searchDates.start}&to=${this.$store.state.searchDates.end}`
                );

                // Each time this method is called we will push the 
                // new page to the vehicles array
                this.vehicles.push(...vehicles.data.data);

                // Each time this method is called we update the last_page from
                // the laravel paginator.
                this.lastPage = vehicles.data.meta.last_page;

                this.loading = false;
            },

            handleScrolledToBottom(isVisible) {
                if (!isVisible) { return };

                // If we are on the last page return
                if (this.page >= this.lastPage) { 
                    return;
                };

                this.page++;

                this.fetchVehicles();
            }
        },

        created() {
            // Set the dates based on query strings, we do this so manual changes
            // to the URL are reflected in the local store
            if (this.$route.query.start && this.$route.query.end) {

                this.$store.dispatch('setSearchDates', {
                    start: this.$route.query.start,
                    end: this.$route.query.end
                });

            };

            // If there is no query string
            this.range.start = dateSetterStart(this.$store.state.searchDates.start);

            this.range.end = dateSetterEnd(this.$store.state.searchDates.end);

            // Get listing of vehicles
            this.fetchVehicles();
        }
    }
</script>