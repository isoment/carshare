<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">
                        <span>Manage Vehicles</span>
                        <customer-manage-vehicles-filter></customer-manage-vehicles-filter>
                    </h3>
                </div>
            </div>
        </div>

        <div class="pt-12 mx-6 md:mx-2">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <!-- Card -->
                <div class="shadow-md border border-gray-50 rounded p-2"
                     v-for="vehicle in vehicles" :key="vehicle.id">
                    <div>
                        <div>
                            <div class="h-32"
                                :style="{ 'background-image': 'url(' + 'https://adultswithautism.org.uk/wp-content/uploads/2015/11/driving-your-car.jpg' + ')' }"
                                style="background-size: cover; background-position: 50% 50%;">
                            </div>
                        </div>
                        <div class="pt-3 pb-2 text-xs">
                            Data here
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerManageVehiclesFilter from './dropdowns/CustomerManageVehiclesFilter';

    export default {
        components: {
            CustomerManageVehiclesFilter,
        },

        data() {
            return {
                vehicles: {}
            }
        },

        methods: {
            async vehicleIndex() {
                try {
                    let response = await axios.get('/api/dashboard/index-users-vehicles');
                    console.log(response.data);
                    this.vehicles = response.data.data;
                } catch (error) {
                    console.log(error);
                }

            }
        },

        async created() {
            this.vehicleIndex();
        }
    }
</script>