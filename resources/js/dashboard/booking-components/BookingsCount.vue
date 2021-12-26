<template>
    <div class="mt-2">
        <div class="flex flex-row">
            <div class="p-2 border rounded-md w-1/3 text-center flex flex-col items-center mr-1"
                 v-if="user.host === 1">
                <h6 class="font-boldnosans font-semibold mb-2">
                    {{toggleLabel}}
                </h6>
                <div>
                    <label for="booking-stat-toggle" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" 
                                   id="booking-stat-toggle" 
                                   class="sr-only"
                                   v-model="isHost">
                            <div class="booking-stat-toggle-bg block bg-gray-200 w-14 h-8 rounded-full"></div>
                            <div class="booking-stat-toggle-dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="p-2 border rounded-md w-1/3 text-center flex flex-col items-center mr-1" 
                 v-else>
                <h6 class="font-boldnosans font-semibold mb-2">As renter</h6>
                <div class="relative">
                    <div class="booking-stat-toggle-bg block bg-gray-200 w-14 h-8 rounded-full"></div>
                    <div class="booking-stat-toggle-dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full"></div>
                </div>
            </div>
            <div class="p-2 border rounded-md w-1/3 text-center flex flex-col items-center mr-1">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Total</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-xl text-gray-800 font-bold font-boldnosans header-rating">
                            {{bookingCountFormat(bookingCount)}}
                        </h5>
                    </div>
                    <div class="h-8 w-8 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-car text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-2 border rounded-md w-1/3 text-center flex flex-col items-center ml-1">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Cancelled</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-xl text-gray-800 font-bold font-boldnosans header-rating">
                            {{bookingCountFormat(cancelCount)}}
                        </h5>
                    </div>
                    <div class="h-8 w-8 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-trash-alt text-purple-400 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        props: {
            stats: Object
        },

        computed: {
            ...mapState({
                user: "user"
            }),

            bookingCount() {
                return this.isHost ? this.stats.asHost.bookings : this.stats.asRenter.bookings;
            },

            cancelCount() {
                return this.isHost ? this.stats.asHost.cancels : this.stats.asRenter.cancels;
            },

            toggleLabel() {
                return this.isHost ? 'As host' : 'As renter';
            }
        },

        data() {
            return {
                isHost: false,
            }
        },

        methods: {
            bookingCountFormat(count) {
                if (count > 1000) {
                    return '+999';
                } else {
                    return count;
                }
            },
        }
    }
</script>

<style>
    input:checked ~ .booking-stat-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .booking-stat-toggle-bg {
        background-color: rgb(167, 134, 255);
    }

    @media screen and (max-width: 315px) {
        h6 {
            font-size: 0.8rem;
        }

        h5 {
            font-size: 1rem;
        }
    }
</style>