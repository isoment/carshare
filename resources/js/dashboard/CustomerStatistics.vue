<template>
    <div>
        <main-navigation></main-navigation>
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
        <div class="mt-8">
            <div class="flex items-center justify-end mb-4">
                <span class="mr-2 font-bold font-boldnosans text-gray-700">As Host:</span>
                <label for="booking-stat-toggle" class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" 
                            id="booking-stat-toggle" 
                            class="sr-only"
                            v-model="hostMode">
                        <div class="booking-stat-toggle-bg block bg-gray-200 w-14 h-7 rounded-full"></div>
                        <div class="booking-stat-toggle-dot absolute left-2 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                    </div>
                </label>
            </div>
            <host-statistics v-if="hostMode"></host-statistics>
            <renter-statistics v-else></renter-statistics>
        </div>
        </div>
    </div>
</template>

<script>
    import RenterStatistics from './statistic-components/RenterStatistics.vue';
    import HostStatistics from './statistic-components/HostStatistics.vue';

    export default {
        components: {
            RenterStatistics,
            HostStatistics
        },

        data() {
            return {
                hostMode: false
            }
        }
    }
</script>

<style scoped>
    input:checked ~ .booking-stat-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .booking-stat-toggle-bg {
        background-color: rgb(167, 134, 255);
    }
</style>