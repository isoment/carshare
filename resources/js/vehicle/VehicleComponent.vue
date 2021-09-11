<template>
    <div>

        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div class="text-center mt-8"
             v-if="loading">
            <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
        </div>

        <div v-else>
            <!-- Vehicle Images -->
            <vehicle-images-slider :vehicle-images="vehicleData.vehicle_images"></vehicle-images-slider>

            <div class="my-10 max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3">

                    <!-- Left Pane -->
                    <div class="col-span-2 mr-4">
                        <div class="grid grid-cols-4">
                            <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm
                                        hidden sm:block">
                                The car
                            </div>
                            <div class="col-span-3 flex flex-col mx-2">
                                <h2 class="font-bold text-xl md:text-4xl font-boldnosans">
                                    {{vehicleData.vehicle_make}} {{vehicleData.vehicle_model}} {{vehicleData.year}}
                                </h2>
                                <div class="my-2">
                                    <span class="font-boldnosans font-bold text-lg sm:text-xl">{{vehicleData.vehicle_rating}}</span>
                                    <span><i class="fas fa-star text-purple-500 text-md"></i></span>
                                    <span class="font-light text-sm">({{vehicleData.vehicle_trip_count}} trips)</span>
                                </div>
                                <div class="grid grid-cols-2 text-lg mt-4">
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <img src="/img/safety-seat.svg" alt="" class="h-7 w-7">
                                        </div>
                                        <div class="text-sm sm:text-md">
                                            {{vehicleData.seats}} Seats
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <img src="/img/car-door.svg" alt="" class="h-7 w-7">
                                        </div>
                                        <div class="ml-1 text-sm sm:text-md">
                                            {{vehicleData.doors}} Doors
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 mt-12">
                            <div class="mx-2 sm:mx-3 mb-3 sm:mb-0 text-left sm:text-right mt-2 font-boldnosans font-bold 
                                        tracking-widest text-sm">
                                Hosted by
                            </div>
                            <div class="sm:col-span-3 mx-2">
                                <div class="flex items-center">
                                    <div class="relative">
                                        <img :src="avatar(vehicleData.host_avatar)" alt="avatar" 
                                            class="h-20 w-20 rounded-full">
                                        <div class="absolute bg-white shadow-lg rounded-full border border-gray-200 px-4
                                                    flex top-16">
                                            <span class="mr-1 font-semibold">
                                                {{vehicleData.host_rating}}
                                                </span>
                                            <span><i class="fas fa-star text-purple-500 text-md"></i></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-boldnosans font-bold text-lg">
                                            {{vehicleData.host_name}}
                                        </h3>
                                        <div class="text-sm">
                                            <span>{{numberFormatComma(vehicleData.host_total_trips)}} trips</span> • 
                                            <span>Joined {{dateFormat(vehicleData.member_since)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 mt-12">
                            <div class="mx-2 sm:mx-3 mb-3 sm:mb-0 text-left sm:text-right mt-2 font-boldnosans font-bold tracking-widest
                                        text-sm">
                                Description
                            </div>
                            <div class="sm:col-span-3 mx-2">
                                <p>
                                    {{vehicleData.description}}
                                </p>
                            </div>                        
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 mt-12">
                            <div class="mx-2 sm:mx-3 mb-3 sm:mb-0 text-left sm:text-right mt-2 font-boldnosans font-bold 
                                        tracking-widest text-sm">
                                Ratings and reviews
                            </div>
                            <div class="sm:col-span-3 mx-2">
                                <!-- Total rating and review count -->
                                <div>
                                    <div class="flex items-center">
                                        <span class="font-boldnosans font-bold text-3xl mr-1">{{vehicleData.vehicle_rating}}</span>
                                        <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        ({{vehicleData.vehicle_review_count}} ratings)
                                    </div>
                                </div>

                                <!-- Vehicle Reviews -->
                                <div class="mt-6">
                                    <h5 class="font-bold uppercase font-boldnosans text-gray-500 text-sm">Reviews</h5>
                                    <vehicle-review-list></vehicle-review-list>
                                </div>
                            </div>                        
                        </div>
                    </div>

                    <!-- Right Pane -->
                    <div class="row-start-1 md:row-auto mb-10 md:mb-0">
                        <!-- Pricing -->
                        <pricing :pricing="pricing"></pricing>

                        <!-- Check Availability -->
                        <div class="mt-6 flex justify-center">
                            <availability @renderPrice="fetchPricing()"
                                          @availability="setAvailability($event)"></availability>
                        </div>

                        <!-- Add to cart -->
                        <div class="mt-6">
                            <div v-if="isLoggedIn">
                                <button class="text-white font-bold py-2 w-full text-sm 
                                                tracking-widest transition-all duration-200"
                                        :disabled="!availability"
                                        :class="{ 
                                            'bg-gray-400': !availability, 
                                            'bg-purple-500 hover:bg-purple-400': availability 
                                        }"
                                        @click="addToCart">
                                    Add to cart
                                </button>
                            </div>
                            <div v-else>
                                <button class="text-white font-bold py-2 w-full text-sm bg-purple-500 hover:bg-purple-400
                                                tracking-widest transition-all duration-200"
                                        @click="() => this.$router.push({ name: 'login' })">
                                    Login
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import avatarHelper from './../shared/mixins/avatarHelper';
    import VehicleImagesSlider from './../sliders/VehicleImagesSlider';
    import VehicleReviewList from './../review/VehicleReviewList';
    import Availability from './Availability';
    import Pricing from './Pricing';
    import { dateFormatMonthYear } from './../shared/utils/dateFormats';
    import { mapState } from 'vuex'
    import moment from 'moment';

    export default {   
        components: {
            VehicleImagesSlider,
            VehicleReviewList,
            Availability,
            Pricing
        },

        mixins: [avatarHelper],

        computed: {
            ...mapState({
                user: state => state.user,
                isLoggedIn: state => state.isLoggedIn,
                start: state => state.searchDates.start,
                end: state => state.searchDates.end,
                cart: state => state.cart
            })
        },

        data() {
            return {
                loading: false,
                vehicleData: null,
                pricing: null,
                availability: null
            }
        },

        methods: {
            dateFormat(date) {
                return dateFormatMonthYear(date);
            },

            numberFormatComma(number) {
                return new Intl.NumberFormat().format(number)
            },

            async fetchVehicle() {
                this.loading = true;

                this.vehicleData = (await axios.get(`/api/vehicle-show/${this.$route.params.id}`)).data.data

                this.loading = false;
            },

            async fetchPricing() {
                this.pricing = (await axios.get(`/api/vehicle-price/${this.$route.params.id}`, {
                    params: {
                        from: this.start,
                        to: this.end
                    }
                })).data.data
            },

            setAvailability(availability) {
                this.availability = availability;
            },

            addToCart() {
                if (!this.user.drivers_license) {
                    this.$store.dispatch('addNotification', {
                        type: 'error',
                        message: 'Please verify your ID in your profile'
                    });
                } else {
                    if (this.dateRangeConfirmation()) {
                        this.$store.dispatch('addToCart', {
                            vehicle: this.vehicleData,
                            price: this.pricing,
                            dates: {
                                start: this.start,
                                end: this.end
                            }
                        });

                        this.$store.dispatch('addNotification', {
                            type: 'success',
                            message: 'Booking added to cart!'
                        });
                    } else {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'You already have a vehicle on this date!'
                        });
                    }
                }
            },

            // Confirm that the customer doesn't have any bookings for this dates
            dateRangeConfirmation() {
                if (this.cart.items.length === 0) {
                    return true;
                }

                // If there is a cart item for the booking the customer is trying to
                // create this will be iterated above 0
                let count = null;

                this.cart.items.forEach(item => {
                    // The cart dates we are checking against
                    const start = new Date(item.dates.start);
                    const end = new Date(item.dates.end);

                    // The attempted booking dates
                    const bookingStart = new Date(this.start);
                    const bookingEnd = new Date(this.end)

                    if (moment(end).isSameOrAfter(bookingStart) && moment(start).isSameOrBefore(bookingEnd)) {
                        count++;
                    }
                });

                return count ? false : true;
            }
        },

        created() {
            this.fetchVehicle();
            this.fetchPricing();
        }
    }
</script>