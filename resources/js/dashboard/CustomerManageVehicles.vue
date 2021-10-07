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

            <div class="text-center" v-if="loading">
                <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2" v-else>
                <!-- Card -->
                <div class="shadow-md border border-gray-50 rounded p-2"
                     v-for="vehicle in vehicles" :key="vehicle.id">
                    <div>
                        <div class="relative">
                            <div class="h-32"
                                :style="{ 'background-image': 'url(' + vehicle.image + ')' }"
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
                                <div>${{vehicle.price_day}} / Day</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import CustomerManageVehiclesFilter from './dropdowns/CustomerManageVehiclesFilter';
    import { monthDayYearNumbericSlash } from './../shared/utils/dateFormats';

    export default {
        components: {
            CustomerManageVehiclesFilter,
        },

        data() {
            return {
                apiParams: {},
                vehicles: null,
                loading: false
            }
        },

        methods: {
            async vehicleIndex() {
                try {
                    this.loading = true;

                    let response = await axios.get('/api/dashboard/index-users-vehicles', {
                        params: {
                            active: this.apiParams.active,
                            priceSort: this.apiParams.priceSort
                        }
                    });

                    console.log(response.data);

                    this.vehicles = response.data.data;

                    this.loading = false;
                } catch (error) {
                    this.$store.dispatch('addNotification', {
                        type: 'error',
                        message: 'Error getting vehicles.'
                    })
                }

            },

            dateFormat(date) {
                return monthDayYearNumbericSlash(date);
            },

            updateVehicles(payload) {
                this.apiParams = payload;
                this.vehicleIndex();
            }
        },
    }
</script>