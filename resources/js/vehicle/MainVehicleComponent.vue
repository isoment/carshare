<template>
    <div>
        <main-navigation></main-navigation>
        <div class="mx-auto mb-6">
            <div class="pt-3 px-2 text-gray-100 shadow-md sticky top-0 bg-white 
                        main-vehicle-filter-bar">
                <div class="relative">
                    <div>
                        <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2
                                    text-gray-700 hover:bg-gray-100 hover:border-gray-100
                                    transition-all duration-300 focus:outline-none"
                                @click="toggleFilterDropdown()">
                            <i class="fas fa-sliders-h"></i>
                            <div class="font-bold font-mono text-sm ml-2">
                                Filter
                            </div>
                        </button>
                    </div>
                    <transition name="fade">
                        <div class="absolute bg-white rounded-md shadow-2xl w-80 md:w-96 border border-gray-300 
                                    top-12 left-4 px-4 py-2"
                             v-if="filterDropdown"
                             v-click-outside="closeFilterDropdown">

                            <!-- Make filter -->
                            <div class="w-full mt-5 mb-10">
                                <h2 class="text-lg font-bold text-gray-800 mb-3">Vehicle make</h2>
                                <v-select :options="makes"
                                          v-model="selectMake"
                                          class="main-vehicle-make-dropdown"
                                          @input="updateMake()">
                                </v-select>
                            </div>

                            <!-- Dates -->
                            <div class="w-full mt-5 mb-10">
                                <h2 class="text-lg font-bold text-gray-800 mb-3">Set Dates</h2>
                                <date-picker v-model="range" 
                                            color="purple" 
                                            is-range
                                            :min-date="minDate"
                                            :max-date="maxDate"
                                            :disabled-dates="bookedDates"
                                            @input="updateDates()">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <div class="flex items-center w-full">
                                            <div class="flex items-center border-b border-gray-300 w-1/2">
                                                <div>
                                                    <label for="from" class="text-purple-500 font-bold text-sm">
                                                        From
                                                    </label>
                                                </div>
                                                <input type="text" name="from" 
                                                    class="ml-4 main-vehicle-date-input focus:outline-none"
                                                    :value="inputValue.start"
                                                    v-on="inputEvents.start">
                                            </div>
                                            <div class="flex items-center border-b border-gray-300 ml-3 w-1/2">
                                                <div>
                                                    <label for="until" class="text-purple-500 font-bold text-sm">
                                                        Until
                                                    </label>
                                                </div>
                                                <input type="text" name="until" 
                                                    class="ml-4 main-vehicle-date-input focus:outline-none"
                                                    :value="inputValue.end"
                                                    v-on="inputEvents.end">
                                            </div>
                                        </div>
                                    </template>
                                </date-picker>
                            </div>

                            <!-- Price filter -->
                            <div class="w-full mt-5 mb-10">
                                <h2 class="text-lg font-bold text-gray-800 mb-3">Filter by price</h2>
                                <h4 class="font-bold text-sm mb-2 text-gray-500">
                                    ${{ priceRange[0] }} - ${{ priceRange[1] }} / Day
                                </h4>
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

                            <!-- Order By -->
                            <div class="w-full mt-5 mb-5">
                                <h2 class="text-lg font-bold text-gray-800 mb-3">Order By</h2>
                                <div class="flex">
                                    <button class="text-xs font-bold rounded-sm text-white px-2 
                                                   py-1 focus:outline-none"
                                            @click="updateOrderBy('popularity')"
                                            :class="orderBy === 'popularity' ? 'bg-purple-600' : 'bg-purple-400'">
                                        Popularity
                                    </button>
                                    <button class="text-xs font-bold rounded-sm text-white bg-purple-400 
                                                   px-2 py-1 ml-2 focus:outline-none"
                                            @click="updateOrderBy('priceLow')"
                                            :class="orderBy === 'priceLow' ? 'bg-purple-600' : 'bg-purple-400'">
                                        Price - Low
                                    </button>
                                    <button class="text-xs font-bold rounded-sm text-white bg-purple-400 
                                                   px-2 py-1 ml-2 focus:outline-none"
                                            @click="updateOrderBy('priceHi')"
                                            :class="orderBy === 'priceHi' ? 'bg-purple-600' : 'bg-purple-400'">
                                        Price - High
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>

            <div>
                <!-- Vehicle Index -->
                <div class="main-vehicle-index">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mx-4 mt-4">
                        <div class="shadow-lg border border-gray-50 rounded-md"
                            v-for="vehicle in vehicles" 
                            :key="vehicle.id">
                            <router-link :to="{ name: 'vehicle', params: { id: vehicle.id } }" target="_blank">
                                <div>
                                    <div class="h-80 lg:h-56 2xl:h-80 rounded-t-lg"
                                        :style="{ 'background-image': 'url(' + vehicle.featured_image + ')' }"
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
                                        ${{ priceFormat(vehicle.price_day) }} / Day
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>

                    <div class="text-center mt-8"
                        v-if="loading">
                        <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
                    </div>
                </div>

                <!-- Map -->
                <div class="main-vehicle-map fixed">
                    <div class="main-vehicle-map-container">
                        <gmap-map :center="mapCenter"
                                  :zoom="12"
                                  style="width: 100%; height: 100%"
                                  :options="googleMapOptions()">
                            <gmap-info-window :options="infoWindowOptions"
                                              :position="infoWindowPosition"
                                              :opened="infoWindowOpened"
                                              @closeclick="handleInfoWindowClose()">
                                <div>
                                    <router-link v-if="typeof activeVehicle.id !== 'undefined'"
                                                 :to="{ name: 'vehicle', params: { id: activeVehicle.id } }" 
                                                 target="_blank">
                                        <div class="mt-2">
                                            <h3 class="font-bold font-boldnosans text-lg">
                                                {{ activeVehicle.year }} {{ activeVehicle.vehicle_make }}
                                            </h3>
                                            <h3 class="font-bold font-boldnosans text-sm">
                                                 {{ activeVehicle.model }} 
                                            </h3>
                                        </div>
                                        <div class="mt-2">
                                            <div class="h-28 w-48"
                                                :style="{ 'background-image': 'url(' + activeVehicle.featured_image + ')' }"
                                                style="background-size: cover; background-position: 50% 50%;">
                                            </div>
                                        </div>
                                        <div class="text-right font-bold text-sm text-purple-500 mt-3">
                                            ${{ priceFormat(activeVehicle.price_day) }} / Day
                                        </div>
                                    </router-link>
                                </div>
                            </gmap-info-window>
                            <gmap-marker v-for="vehicle in vehicles"
                                         :key="vehicle.id"
                                         :position="{
                                             lat:formatCoord(vehicle.latitude), 
                                             lng:formatCoord(vehicle.longitude)
                                         }"
                                         :clickable="true"
                                         :draggable="false"
                                         :icon="{url : 'img/purple-dot.png'}"
                                         @click="handleMarkerClicked(vehicle)">
                            </gmap-marker>
                        </gmap-map>
                    </div>
                </div>
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
    import { mapState } from 'vuex';
    import moment from 'moment';
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import VueSlider from 'vue-slider-component';
    import 'vue-slider-component/theme/material.css';
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';
    import {gmapApi} from 'vue2-google-maps';

    import vehicleSearchDatesComputed from  './../shared/mixins/vehicleSearchDatesComputed';
    import calendarMinMaxDate from './../shared/mixins/calendarMinMaxDate';
    import { wholeDollars } from './../shared/utils/currency';


    export default {
        components: {
            Calendar,
            DatePicker,
            VueSlider,
            vSelect
        },

        mixins: [vehicleSearchDatesComputed, calendarMinMaxDate],

        computed: {
            google: gmapApi,

            // Can also calculate the center point for all coordinates
            // or form a bounding box based on the largest or smallest.
            mapCenter() {
                if (!this.vehicles.length) {
                    return {
                        lat: 41.877600,
                        lng: -87.673700
                    }
                }

                return {
                    lat: parseFloat(this.vehicles[0].latitude),
                    lng: parseFloat(this.vehicles[0].longitude)
                }
            },

            infoWindowPosition() {
                return {
                    lat: parseFloat(this.activeVehicle.latitude),
                    lng: parseFloat(this.activeVehicle.longitude)
                }
            },

            ...mapState({
                bookedDates: state => state.bookedDates
            })
        },

        data() {
            return {
                loading: false,
                vehicles: [],
                page: 1,
                lastPage: 1,
                endOfResults: false,
                priceRange: [],
                maxPrice: 1000,
                minPrice: 0,
                vehicleMake: 'all',
                filterDropdown: false,
                makes: null,
                selectMake: null,
                orderBy: 'popularity',
                infoWindowOptions: {
                    pixelOffset: {
                        width: 0,
                        height: -35
                    }
                },
                activeVehicle: {},
                infoWindowOpened: false
            }
        },

        methods: {
            priceFormat(value) {
                return wholeDollars(value);
            },

            // Update the query strings. Don't log the error router throws 
            // when navigating to same page if the query string isn't updated.
            refreshPage() {
                this.$router.push({
                    name: 'main-vehicle',
                    query: {
                        start: this.$store.state.searchDates.start,
                        end: this.$store.state.searchDates.end,
                        make: this.vehicleMake,
                        orderBy: this.orderBy
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

            async fetchVehicles() {
                this.loading = true;

                try {
                    let vehicles = await axios.get('/api/vehicles-index', {
                        params: {
                            page: this.page,
                            from: this.$store.state.searchDates.start,
                            to: this.$store.state.searchDates.end,
                            min: this.$store.state.priceRange.min,
                            max: this.$store.state.priceRange.max,
                            make: this.vehicleMake,
                            orderBy: this.orderBy
                        }
                    });

                    // Each time this method is called we will push the new page to the vehicles
                    // array and update the last page.
                    this.vehicles.push(...vehicles.data.data);
                    this.lastPage = vehicles.data.meta.last_page;
                } catch (error) {
                    // If the dates are invalid reset to the defaults. Refresh the url query
                    // strings and fetch the vehicles again.
                    if (error.response && error.response.status === 422) {
                        let start = moment().add(2, 'days').format('MM/DD/YYYY');
                        let end = moment(start, 'MM/DD/YYYY').add(2, 'days').format('MM/DD/YYYY');

                        this.$store.dispatch('setSearchDates', {
                            start: start,
                            end: end
                        });

                        this.refreshPage();

                        this.fetchVehicles();
                    }
                }

                this.loading = false;
            },

            async fetchMakes() {
                this.loading = true;

                let response = (await axios.get('/api/vehicle-make/list')).data.data;
                let array = ['All'];

                response.forEach(item => {
                    array.push(item.make);
                });

                this.makes = array;

                this.loading = false;
            },

            updatePriceRange() {
                // Call action to set the local storage.
                this.$store.dispatch('setPriceRange', {
                    min: this.priceRange[0],
                    max: this.priceRange[1]
                });

                // Clear the vehicles array, set the page page to 1
                // and then fetch the vehicles.
                this.vehicles = [];
                this.page = 1;
                this.fetchVehicles();
            },

            updateDates() {
                this.refreshPage();
                this.vehicles = [];
                this.page = 1;
                this.fetchVehicles();
            },

            updateMake() {
                const newMake = this.selectMake.toLowerCase()

                this.refreshPage();

                this.vehicleMake = newMake;

                this.vehicles = [];
                this.page = 1;
                this.fetchVehicles();
            },

            updateOrderBy(order) {
                const valid = ['popularity', 'priceLow', 'priceHi'];

                if (valid.values(order)) {
                    this.orderBy = order
                } else {
                    this.orderBy = 'popularity';
                }

                this.refreshPage();

                this.vehicles = [];
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
            },

            handleQueryStrings() {
                // Set the dates based on query strings, we do this so manual changes
                // to the URL are reflected in the local store.
                if (this.$route.query.start && this.$route.query.end) {
                    this.$store.dispatch('setSearchDates', {
                        start: this.$route.query.start,
                        end: this.$route.query.end
                    });
                }

                if (this.$route.query.make) {
                    this.vehicleMake = this.$route.query.make;
                } else {
                    this.vehicleMake = 'all';
                }

                if (this.$route.query.orderBy) {
                    this.orderBy = this.$route.query.orderBy;
                } else {
                    this.orderBy = 'popularity';
                }
            },

            // Set a base price range based on the most and least expensive vehicles
            async setPriceRange() {
                try {
                    const prices = await axios.get('/api/vehicles/price-range');
                    this.priceRange = Array(Number(prices.data.max), Number(prices.data.min));

                    this.$store.dispatch('setPriceRange', {
                        min: prices.data.min,
                        max: prices.data.max
                    });

                    // Set the min and max price state.
                    this.minPrice = Number(prices.data.min);
                    this.maxPrice = Number(prices.data.max);
                } catch (error) {
                    this.$store.dispatch('addNotification', {
                        type: 'error',
                        message: 'Error setting price range'
                    })
                }
            },

            toggleFilterDropdown() {
                this.filterDropdown = !this.filterDropdown;
            },

            closeFilterDropdown() {
                this.filterDropdown = false;
            },

            setSelectedMake() {
                const makeFromRoute = this.$route.query.make
                this.selectMake = makeFromRoute.charAt(0).toUpperCase() + makeFromRoute.slice(1);
            },

            googleMapOptions() {
                return {
                    fullscreenControl: false,
                    streetViewControl: false,
                    zoomControl: true,
                    zoomControlOptions: {
                        position: this.google && this.google.maps.ControlPosition.RIGHT_TOP,
                    },
                    styles: [
                        {
                            featureType: 'poi',
                            stylers: [
                                {visibility: 'off'}
                            ]   
                        }
                    ],
                    scrollwheel: true
                }
            },

            formatCoord(coordinate) {
                return parseFloat(coordinate);
            },

            handleMarkerClicked(vehicle) {
                this.activeVehicle = vehicle;
                this.infoWindowOpened = true;
            },

            handleInfoWindowClose() {
                this.activeVehicle = {};
                this.infoWindowOpened = false;
            }
        },

        created() {
            this.handleQueryStrings();
            this.fetchMakes();
            this.$store.dispatch('checkSearchDates');
            this.$store.dispatch('setUserBookedDates');
            this.refreshPage();
            this.setSelectedMake();
            this.setPriceRange();
            this.fetchVehicles();
        },
    }
</script>

<style>
    .main-vehicle-make-dropdown .vs__dropdown-menu {
        max-height: 250px;
    }

    .main-vehicle-make-dropdown .vs__dropdown-toggle  {
        border: none;
        border-bottom: 1px solid #d2d1d1;
        padding: 0 0 10px 0;
    }

    .main-vehicle-make-dropdown .vs__selected  {
        padding: 0;
        margin: 0;
    }

    .main-vehicle-make-dropdown .vs__search  {
        padding: 0;
        margin: 0;
    }

    .main-vehicle-date-input {
        color: black;
    }

    .main-vehicle-filter-bar {
        padding-bottom: 6px;
    }

    .main-vehicle-index {
        width: 65%;
        z-index: 10;
    }

    .main-vehicle-map {
        height: 95%;
        width: 35%;
        z-index: 50;
        right: 0;
        top: 4rem;
    }

    .main-vehicle-map-container {
        height: 100%;
    }
</style>