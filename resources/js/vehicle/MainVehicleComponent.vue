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
                            :class="{ active: datesMenu }"
                            @click="toggleDatesMenu">
                        <i class="far fa-calendar-alt text-md"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Dates
                        </div>
                    </button>

                    <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2 ml-2
                                text-gray-700 focus:outline-none hover:bg-gray-100 hover:border-gray-100
                                transition-all duration-300"
                            :class="{ active: priceMenu }"
                            @click="togglePriceMenu">
                        <i class="fas fa-dollar-sign"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Price
                        </div>
                    </button>
                    
                    <!-- <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2 ml-2
                                text-gray-700 focus:outline-none hover:bg-gray-100 hover:border-gray-100
                                transition-all duration-300">
                        <i class="fas fa-sliders-h"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Filters
                        </div>
                    </button> -->
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

                <transition name="slide-fade">
                    <div v-show="priceMenu" class="w-full md:w-1/3 my-2">
                        <h4 class="font-bold text-sm mb-2">${{ priceRange[0] }} - ${{ priceRange[1] }} / Day</h4>
                        <vue-slider v-model="priceRange"
                                    :max="maxPrice"
                                    :min="minPrice"
                                    :interval="10"
                                    :enable-cross="false"
                                    :tooltip="'none'"
                                    @drag-end="() => updatePriceRange()"
                                    class="mx-2">
                        </vue-slider>
                    </div>
                </transition>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="shadow-lg border border-gray-50 rounded-lg"
                     v-for="vehicle in vehicles" 
                     :key="vehicle.id">
                    <router-link :to="{ name: 'vehicle', params: { id: vehicle.id } }">
                        <div>
                            <div class="h-56 rounded-t-lg"
                                :style="{ 'background-image': 'url(' + vehicle.image + ')' }"
                                style="background-size: cover; background-position: 50% 50%;">
                            </div>
                        </div>
                        <div class="px-6 pt-3 pb-2">
                            <div>
                                <h3 class="font-bold font-boldnosans text-xl">
                                    {{ vehicle.vehicle_make }} {{ vehicle.model }} {{ vehicle.year }}
                                </h3>
                                <h6 class="font-light text-sm">
                                    Vehicle ID {{ vehicle.id }}, Trips: {{ vehicle.bookings_count }}
                                </h6>
                            </div>
                            <div class="text-right font-bold text-sm text-purple-500 mt-6">
                                ${{ vehicle.price_day }} / Day
                            </div>
                        </div>
                    </router-link>
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
    import VueSlider from 'vue-slider-component';
    import 'vue-slider-component/theme/material.css';

    export default {
        components: {
            Calendar,
            DatePicker,
            VueSlider
        },

        data() {
            return {
                loading: false,
                vehicles: [],
                page: 1,
                lastPage: 1,
                endOfResults: false,
                datesMenu: false,
                priceMenu: false,
                priceRange: [],
                maxPrice: 1000,
                minPrice: 0,
                range: {
                    start: null,
                    end: null
                },
            }
        },

        methods: {
            toggleDatesMenu() {
                if (this.priceMenu) {
                    this.priceMenu = false;
                    setTimeout(function() {
                        this.datesMenu = !this.datesMenu;
                    }.bind(this), 400);
                } else {
                    this.datesMenu = !this.datesMenu;
                }
            },

            togglePriceMenu() {
                if (this.datesMenu) {
                    this.datesMenu = false;
                    setTimeout(function() {
                        this.priceMenu = !this.priceMenu;
                    }.bind(this), 400);
                } else {
                    this.priceMenu = !this.priceMenu;
                }
            },

            // Redirect to same page to update query string
            // Don't log the error router throws when navigating to
            // same page if the query string isn't updated.
            refeshPage() {
                // Get and parse dates from local storage.
                let dates = localStorage.getItem('searchDates');
                let start = JSON.parse(dates).start;
                let end = JSON.parse(dates).end;

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
            },

            updateDates() {
                // Call the action to set local storage.
                this.$store.dispatch('setSearchDates', {
                    start: dateTypeCheck(this.range.start),
                    end: dateTypeCheck(this.range.end)
                });

                this.refeshPage();

                // Clear the vehicles array.
                this.vehicles = [];

                // Reset page to first page.
                this.page = 1;

                this.fetchVehicles();
            },

            async fetchVehicles() {
                this.loading = true;

                let vehicles = await axios.get(
                    `/api/vehicles-index?page=${this.page}
                    &from=${this.$store.state.searchDates.start}
                    &to=${this.$store.state.searchDates.end}
                    &min=${this.$store.state.priceRange.min}
                    &max=${this.$store.state.priceRange.max}`
                );

                // Each time this method is called we will push the 
                // new page to the vehicles array.
                this.vehicles.push(...vehicles.data.data);

                // Each time this method is called we update the last_page from
                // the laravel paginator.
                this.lastPage = vehicles.data.meta.last_page;

                this.loading = false;
            },

            updatePriceRange() {
                // Call action to set the local storage.
                this.$store.dispatch('setPriceRange', {
                    min: this.priceRange[0],
                    max: this.priceRange[1]
                });

                // Clear the vehicles array.
                this.vehicles = [];

                // Reset page to first page.
                this.page = 1;

                this.fetchVehicles();
            },

            handleScrolledToBottom(isVisible) {
                if (!isVisible) { return };

                // If we are on the last page return.
                if (this.page >= this.lastPage) { 
                    return;
                };

                this.page++;

                this.fetchVehicles();
            }
        },

        async created() {
            // Set the dates based on query strings, we do this so manual changes
            // to the URL are reflected in the local store.
            if (this.$route.query.start && this.$route.query.end) {

                this.$store.dispatch('setSearchDates', {
                    start: this.$route.query.start,
                    end: this.$route.query.end
                });

            }

            // Set the date range and dispatch an event to vuex to store them
            let start = dateSetterStart(this.$store.state.searchDates.start);
            let end = dateSetterEnd(this.$store.state.searchDates.end);

            this.range.start = start;
            this.range.end = end;

            this.$store.dispatch('setSearchDates', {
                start: start,
                end: end
            });

            // We want to update the query strings each time the component is created
            this.refeshPage();

            // We need to get the min and max vehicle prices from the api.
            let prices = await axios.get('/api/vehicles/price-range');

            // Set this to the price range state
            this.priceRange = Array(Number(prices.data.max), Number(prices.data.min));

            this.$store.dispatch('setPriceRange', {
                min: prices.data.min,
                max: prices.data.max
            });

            // Set the min and max price state.
            this.minPrice = Number(prices.data.min);
            this.maxPrice = Number(prices.data.max);

            this.fetchVehicles();
        },
    }
</script>

<style scoped>
    .active {
        color: rgb(139, 92, 246);
    }
</style>