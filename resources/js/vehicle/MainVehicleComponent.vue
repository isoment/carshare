<template>
    <div>
        <!-- <main-navigation></main-navigation> -->
        <div class="mx-auto mb-6">
            <div class="pt-2 px-2 text-gray-100 shadow-sm sticky top-0 bg-white 
                        main-vehicle-filter-bar flex items-center justify-between">
                <div class="flex items-center">
                    <div class="relative">
                        <div>
                            <button class="border rounded-md border-gray-300 flex items-center py-2 px-3
                                        text-gray-700 hover:bg-gray-100 hover:border-gray-100
                                        transition-all duration-300 focus:outline-none"
                                    @click="toggleFilterDropdown()">
                                <i class="fas fa-sliders-h"></i>
                                <div class="font-bold font-mono text-sm ml-2">
                                    Filter
                                </div>
                            </button>
                        </div>
                        <div>
                            <div v-if="filterDropdown"
                                 class="main-vehicle-filter-menu-bkgr">
                            </div>
                            <div class="absolute bg-white rounded-sm shadow-2xl w-80 md:w-96 border border-gray-300 
                                        top-12 left-4 px-4 py-2 main-vehicle-filter-dropdown"
                                v-if="filterDropdown"
                                v-click-outside="closeFilterDropdown">

                                <div class="w-full mt-5 mb-10">
                                    <h2 class="text-lg font-bold text-gray-800 mb-3">Vehicle make</h2>
                                    <v-select :options="makes"
                                            v-model="selectMake"
                                            class="main-vehicle-make-dropdown"
                                            @input="updateMake()">
                                    </v-select>
                                </div>

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
                                                        v-on="inputEvents.start"
                                                        readonly>
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
                                                        v-on="inputEvents.end"
                                                        readonly>
                                                </div>
                                            </div>
                                        </template>
                                    </date-picker>
                                </div>

                                <div class="w-full mt-5 mb-10">
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

                                <div class="mt-10 mb-5">
                                    <button class="px-3 py-2 text-white bg-purple-500 font-bold focus:outline-none"
                                            @click="closeFilterDropdown()">
                                        View results
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <router-link class="px-3 py-2 rounded-md text-sm font-bold text-gray-700"
                                     :to="{ name: 'main-page' }">
                            Home
                        </router-link>
                    </div>
                </div>
                <div class="flex items-center">
                    <demo-info :hoverColor="'purple-400'"></demo-info>
                    <shopping-cart-link :hoverColor="'purple-400'" v-if="isLoggedIn"></shopping-cart-link>
                    <profile-dropdown :focusRingColor="'white'" v-if="isLoggedIn"></profile-dropdown>
                    <div class="text-purple-500 border border-purple-500 rounded-sm font-bold text-sm 
                                px-3 py-1 hover:text-purple-600 hover:border-purple-600 hover:shadow-md
                                transition-all duration-200"
                         v-if="!isLoggedIn">
                        <router-link :to="{ name: 'login' }">
                            Login
                        </router-link>
                    </div>
                </div>
            </div>

            <div>
                <!-- Vehicle Index -->
                <div class="main-vehicle-index"
                     v-if="!mobileMapOpen">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mx-4 mt-4">
                        <div class="shadow-lg border border-gray-50 rounded-sm"
                            v-for="vehicle in vehicles" 
                            :key="vehicle.id">
                            <router-link :to="{ name: 'vehicle', params: { id: vehicle.id } }" target="_blank">
                                <div>
                                    <div class="lg:h-56 2xl:h-80 rounded-t-lg index-card-img"
                                        :style="{ 'background-image': 'url(' + vehicle.featured_image + ')' }"
                                        style="background-size: cover; background-position: 50% 50%;">
                                    </div>
                                </div>
                                <div class="px-6 pt-3 pb-2">
                                    <div>
                                        <h3 class="font-bold font-boldnosans text-xl">
                                            {{ vehicle.vehicle_make }} {{ vehicle.model }} {{ vehicle.year }}
                                        </h3>
                                        <h6 class="text-xs font-semibold text-gray-600">
                                            ({{ vehicle.bookings_count }} Trips)
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
                <div class="main-vehicle-map"
                     v-if="mobileMapOpen || openDesktopMap">
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
                                         :icon="mapDotType(vehicle.id)"
                                         @click="handleMarkerClicked(vehicle)">
                            </gmap-marker>
                        </gmap-map>
                    </div>
                </div>
            </div>

            <div class="mobile-toggle block lg:hidden" v-if="displayMapListToggle">
                <div class="bg-purple-700 rounded-full text-white shadow-md">
                    <button class="uppercase font-semibold focus:outline-none px-6 py-3"
                            @click="toggleMobileMap()">
                        <div v-if="mobileMapOpen">
                            <i class="fas fa-list mr-2"></i>
                            <span>List</span>
                        </div>
                        <div v-else class="flex items-center">
                            <i class="fas fa-map-marked-alt mr-2"></i>
                            <span>Map</span>
                        </div>
                    </button>
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
    import ProfileDropdown from '../shared/components/ProfileDropdown.vue';
    import ShoppingCartLink from '../shared/components/ShoppingCartLink.vue';
    import DemoInfo from '../shared/components/DemoInfo.vue';

    import vehicleSearchDatesComputed from  './../shared/mixins/vehicleSearchDatesComputed';
    import calendarMinMaxDate from './../shared/mixins/calendarMinMaxDate';
    import { wholeDollars } from './../shared/utils/currency';


    export default {
        components: {
            ProfileDropdown,
            ShoppingCartLink,
            DemoInfo,
            Calendar,
            DatePicker,
            VueSlider,
            vSelect
        },

        mixins: [vehicleSearchDatesComputed, calendarMinMaxDate],

        computed: {
            ...mapState({
                bookedDates: state => state.bookedDates,
                isLoggedIn: "isLoggedIn"
            }),

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

            openDesktopMap() {
                return this.windowWidth > 1024 ? true : false;
            }
        },

        watch: {
            windowWidth: function(newWindowWidth) {
                if (newWindowWidth > 1024) {
                    this.mobileMapOpen = false;
                }
            },

            // Disable scroll when the filterDropdown is open
            filterDropdown: function() {
                if (this.filterDropdown) {
                    document.documentElement.style.overflow = 'hidden';
                    return;
                }

                document.documentElement.style.overflow = 'auto';
            }
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
                infoWindowOpened: false,
                clickedMarkers: [],
                mobileMapOpen: false,
                displayMapListToggle: true,
                windowWidth: window.innerWidth
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
                const newMake = this.selectMake.toLowerCase();

                this.vehicleMake = newMake;

                this.refreshPage();

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

                if (this.filterDropdown) {
                    this.displayMapListToggle = false;
                }
            },

            closeFilterDropdown() {
                this.filterDropdown = false;

                this.displayMapListToggle = true;
            },

            setSelectedMake() {
                const makeFromRoute = this.$route.query.make
                this.selectMake = makeFromRoute.charAt(0).toUpperCase() + makeFromRoute.slice(1);
            },

            googleMapOptions() {
                return {
                    fullscreenControl: false,
                    streetViewControl: false,
                    mapTypeControl: false,
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

                if (!this.clickedMarkers.includes(vehicle.id)) {
                    this.clickedMarkers.push(vehicle.id);
                }
            },

            handleInfoWindowClose() {
                this.activeVehicle = {};
                this.infoWindowOpened = false;
            },

            mapDotType(id) {
                return this.clickedMarkers.includes(id) ? 
                    {url : 'img/dot-light.png'} :
                    {url : 'img/dot-dark.png'};
            },

            addResizeEvent() {
                window.addEventListener('resize', () => {
                    this.windowWidth = window.innerWidth;
                });
            },

            toggleMobileMap() {
                this.mobileMapOpen = !this.mobileMapOpen;
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
            this.addResizeEvent();
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

    .main-vehicle-make-dropdown .vs__clear {
        display: none;
    }

    .main-vehicle-make-dropdown .vs__selected {
        font-weight: bold;
    }

    .main-vehicle-date-input {
        color: black;
    }

    /* Apply a z-index so the dropdown appears over map */
    .main-vehicle-filter-bar {
        padding-bottom: 8px;
        z-index: 100;
    }

    .main-vehicle-index {
        z-index: 10;
    }

    .mobile-toggle {
        position: fixed;
        z-index: 110;
        bottom: 0.8rem;
        left: 50%;
        transform: translateX(-50%);
    }

    .main-vehicle-map-container {
        height: 100%;
    }

    .main-vehicle-map {
        position: fixed;
        height: 95.5%;
        width: 100%;
        z-index: 50;
        right: 0;
        top: 3.4rem;
    }

    .main-vehicle-filter-menu-bkgr {
        position: fixed;
        background: white;
        opacity: 0.68;
        height: 95.5%;
        width: 100%;
        z-index: 119;
        right: 0;
        top: 3.4rem;
    }

    .main-vehicle-filter-dropdown {
        z-index: 120;
        background-color: white;
    }

    @media screen and (max-width: 1024px) {
        .main-vehicle-index .index-card-img {
            height: 30rem;
        }
    }

    @media screen and (max-width: 650px) {
        .main-vehicle-index .index-card-img {
            height: 24rem;
        }
    }

    @media screen and (max-width: 500px) {
        .main-vehicle-index .index-card-img {
            height: 16rem;
        }
    }

    @media screen and (min-width: 1024px) {
        .main-vehicle-index {
            width: 65%;
        }

        .main-vehicle-map {
            width: 35%;
        }
    }
</style>