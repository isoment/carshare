<template>
    <div>
        <main-navigation></main-navigation>
        <div v-if="!isLoggedIn">
            <error :message="'Not Authorized'"></error>
        </div>
        <div>
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
                <div v-if="loading" class="text-center mt-8">
                    <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
                </div>
                <div v-else>
                    <host-statistics v-if="hostMode"></host-statistics>
                    <renter-statistics v-else :stats="stats"></renter-statistics>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RenterStatistics from './statistic-components/RenterStatistics.vue';
    import HostStatistics from './statistic-components/HostStatistics.vue';
    import { mapState } from 'vuex';

    export default {
        components: {
            RenterStatistics,
            HostStatistics
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),
        },

        data() {
            return {
                hostMode: false,
                stats: null,
                loading: false
            }
        },

        methods: {
            async fetchRenterStats() {
                try {
                    this.loading = true;
                    let response = await axios.get('/api/dashboard/renter-stats');
                    this.stats = response.data;
                } catch (error) {
                    console.log(error);
                }
                this.loading = false;
            }
        },

        created() {
            this.fetchRenterStats();
        },
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