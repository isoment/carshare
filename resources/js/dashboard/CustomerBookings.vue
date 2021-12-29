<template>
    <div>
        <main-navigation></main-navigation>
        <div v-if="!isLoggedIn">
            <error :message="'Not Authorized'"></error>
        </div>
        <div v-else>
                <div class="customer-profile-banner h-36 border-b border-gray-200 pb-8"></div>

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="relative md:mr-20">
                        <div class="absolute -top-8 mb-12">
                            <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                                <h3 class="text-lg font-boldnosans font-bold">Bookings</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="mt-6 sm:mt-12">
                        <!-- Select small screen -->
                        <div class="mb-2 text-right w-full relative">
                            <button class="focus:outline-none px-2 py-1 border border-gray-800 text-gray-800"
                                    @click="toggleFilterMenu">
                                <span class="text-sm font-semibold">Filter</span>
                                <span><i class="fas fa-sliders-h text-sm"></i></span>
                            </button>

                            <div class="absolute bg-white right-0 top-10 rounded-sm z-40 w-48
                                        border border-gray-200 filter-dropdown-boxshadow"
                                 v-if="filterMenu"
                                 v-click-outside="filterMenuClose">
                                <div class="px-3 py-5">
                                    <h5 class="text-left text-xs font-semibold mb-1">Type:</h5>
                                    <select class="w-full bg-white border border-gray-300 
                                                   rounded-sm text-sm focus:outline-none py-1"
                                            v-model="params.type"
                                            @change="fetchBookings()">
                                        <option value="asRenter">As Renter</option>
                                        <option value="asHost">As Host</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-5">
                            <!-- Select large screen -->
                            <div class="border-l border-gray-300 pl-3 hidden sm:block large-screen-booking-stats" 
                                 v-if="bookingCounts">
                                <!-- Users reviews of hosts -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-boldnosans font-semibold tracking-wider text-purple-500 underline">
                                        As Renter
                                    </h4>
                                    <div class="mt-1">
                                        <h6 class="font-boldnosans font-light text-sm">Total</h6>
                                        <div class="text-2xl font-boldnosans font-semibold text-gray-700">
                                            {{bookingCountFormat(bookingCounts.asRenter.bookings)}}
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <h6 class="font-boldnosans font-light text-sm">Cancelled</h6>
                                        <div class="text-2xl font-boldnosans font-semibold text-gray-700">
                                            {{bookingCountFormat(bookingCounts.asRenter.cancels)}}
                                        </div>
                                    </div>
                                </div>
                                <!-- Users reviews of renters -->
                                <div v-if="userIsHost">
                                    <h4 class="text-lg font-boldnosans font-semibold tracking-wider text-purple-500 underline">
                                        As Host
                                    </h4>
                                    <div class="mt-1">
                                        <h6 class="font-boldnosans font-light text-sm">Total</h6>
                                        <div class="text-2xl font-boldnosans font-semibold text-gray-700">
                                            {{bookingCountFormat(bookingCounts.asHost.bookings)}}
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <h6 class="font-boldnosans font-light text-sm">Cancelled</h6>
                                        <div class="text-2xl font-boldnosans font-semibold text-gray-700">
                                            {{bookingCountFormat(bookingCounts.asHost.cancels)}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews -->
                            <div class="col-span-5 sm:col-span-4">
                                <div class="sm:hidden mb-4">
                                    <bookings-count v-if="bookingCounts" :stats="bookingCounts"></bookings-count>
                                </div>
                                <div class="mt-2" v-if="bookings">
                                    <!-- Paginator -->
                                    <simple-paginator :iterable="bookings"
                                                    @pageChanged="pageChanged">
                                        <display-booking-renter :bookings="bookings"
                                                                v-if="displayRenterComponent">
                                        </display-booking-renter>
                                    </simple-paginator>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import BookingsCount from './booking-components/BookingsCount.vue';
    import SimplePaginator from './../shared/components/SimplePaginator.vue';
    import DisplayBookingRenter from './booking-components/DisplayBookingRenter.vue';

    export default {
        components: {
            BookingsCount,
            SimplePaginator,
            DisplayBookingRenter
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            userIsHost() {
                return this.user.host === 1;
            },

            displayRenterComponent() {
                return this.params.type === 'asRenter';
            }
        },

        data() {
            return {
                filterMenu: false,
                bookingCounts: null,
                bookings: null,
                page: 1,
                params: {
                    type: 'asRenter'
                }
            }
        },

        methods: {
            toggleFilterMenu() {
                this.filterMenu = !this.filterMenu;
            },

            filterMenuClose() {
                this.filterMenu = false;
            },

            bookingCountFormat(count) {
                if (count > 1000) {
                    return '+999';
                } else {
                    return count;
                }
            },

            async fetchCounts() {
                try {
                    let results = await axios.get('/api/dashboard/booking-counts');
                    this.bookingCounts = results.data;
                } catch (error) {
                    this.$store.dispatch('addNotification', {
                        type: 'error',
                        message: 'Error, please refresh page'
                    });
                }
            },

            async fetchBookings() {
                try {
                    let results = await axios.get('/api/dashboard/booking-index', {
                        params: {
                            page: this.page,
                            type: this.params.type
                        }
                    });
                    this.bookings = results.data;
                } catch (error) {
                    if (error.response.status === 422) {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: error.response.data.errors.type[0]
                        });
                    }
                }
            },

            pageChanged(payload) {
                this.page = payload;
                this.fetchBookings();
            }
        },

        created() {
            this.fetchCounts();
            this.fetchBookings();
        }
    }
</script>

<style scoped>
    .large-screen-booking-stats {
        max-height: 19rem;
    }

    .filter-dropdown-boxshadow {
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
</style>