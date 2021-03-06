<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">
                        <span>Manage Vehicles</span>
                        <customer-manage-vehicles-filter @inputUpdated="updateVehicles">
                        </customer-manage-vehicles-filter>
                    </h3>
                </div>
            </div>
        </div>

        <div class="pt-12 mx-6 md:mx-2">
            <!-- Loading spinner -->
            <div class="text-center" v-if="loading">
                <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
            </div>

            <!-- No vehicles message -->
            <div class="text-center font-boldnosans font-bold" v-else-if="noVehicles">
                No Vehicles
            </div>

            <!-- Vehicle index -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2" v-else>
                <div class="shadow-md border border-gray-50 rounded p-2"
                     v-for="vehicle in vehicles" :key="vehicle.id">
                    <router-link :to="{ 
                        name: 'customer-edit-vehicle', 
                        params: { id: vehicle.id } 
                    }">
                        <div>
                            <div class="relative">
                                <div class="h-32"
                                    :style="{ 'background-image': 'url(' + vehicle.featured_image + ')' }"
                                    style="background-size: cover; background-position: 50% 50%;">
                                </div>
                                <div class="w-7 h-7 rounded-full bg-white absolute flex items-center justify-center top-1 right-1"
                                    v-if="!vehicle.active">
                                    <i class="fas fa-user-lock text-red-400"></i>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-xs">
                                    <div class="pt-3 pb-1">
                                        <span class="font-bold text-purple-600">{{vehicle.year}}</span> {{vehicle.make}} {{vehicle.model}}
                                    </div>
                                    <div>
                                        In Service: {{dateFormat(vehicle.created_at)}}
                                    </div>
                                </div>
                                <div class="text-xs font-bold">
                                    <div>${{priceFormat(vehicle.price_day)}} / Day</div>
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-3" v-if="multiplePages">
                <div class="flex items-center justify-between">
                    <button class="bg-gray-300 rounded-sm px-3 py-1 text-white text-sm font-bold 
                                  tracking-widest"
                            :class="{ 'bg-purple-400 hover:bg-purple-300 transition-all duration-200': !onFirstPage }"
                            :disabled="onFirstPage"
                            @click="prevPage">
                        Prev
                    </button>
                    <button class="bg-gray-300 rounded-sm px-3 py-1 text-white text-sm font-bold 
                                  tracking-widest"
                            :class="{ 'bg-purple-400 hover:bg-purple-300 transition-all duration-200' : !onLastPage }"
                            :disabled="onLastPage"
                            @click="nextPage">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerManageVehiclesFilter from './dropdowns/CustomerManageVehiclesFilter';
    import { monthDayYearNumbericSlash } from './../shared/utils/dateFormats';
    import { wholeDollars } from './../shared/utils/currency';

    export default {
        components: {
            CustomerManageVehiclesFilter,
        },

        data() {
            return {
                page: 1,
                lastPage: '',
                apiParams: {},
                vehicles: {},
                loading: false
            }
        },

        computed: {
            noVehicles() {
                return this.vehicles.length === 0;
            },

            multiplePages() {
                return this.lastPage > 1;
            },

            onLastPage() {
                return this.page === this.lastPage
            },

            onFirstPage() {
                return this.page === 1;
            }
        },

        methods: {
            async vehicleIndex() {
                try {
                    this.loading = true;

                    let response = (await axios.get(`/api/dashboard/index-users-vehicles?page=${this.page}`, {
                        params: this.apiParams
                    }));

                    this.vehicles = response.data.data;
                    this.lastPage = response.data.meta.last_page;

                    this.loading = false;
                } catch (error) {
                    this.$store.dispatch('addNotification', {
                        type: 'error',
                        message: 'Error getting vehicles.'
                    });
                }

            },

            prevPage() {
                if (this.page > 1) {
                    this.page--
                    this.vehicleIndex();
                }
            },

            nextPage() {
                if (this.page < this.lastPage) {
                    this.page++
                    this.vehicleIndex();
                }
            },

            dateFormat(date) {
                return monthDayYearNumbericSlash(date);
            },

            priceFormat(value) {
                return wholeDollars(value);
            },

            updateVehicles(payload) {
                this.page = 1;
                this.apiParams = payload;
                this.vehicleIndex();
            }
        },
    }
</script>