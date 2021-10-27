<template>
    <div>
        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div v-if="vehicle">

            <!-- Display unauthorized if not logged in -->
            <div v-if="!isLoggedIn">
                <error :message="'Not Authorized'"></error>
            </div>

            <!-- You do not own this vehicle -->
            <div v-else-if="!isVehicleOwner">
                <error :message="'This is not your vehicle'"
                       :routeName="'customer-profile'"
                       :routeDesc="'profile page'">
                </error>
            </div>

            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6" v-else>
                <!-- Loading spinner -->
                <div class="text-center mt-8"
                    v-if="loading">
                    <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 mt-10">
                    <div>
                        <h3 class="text-2xl md:text-3xl font-extrabold">
                            {{vehicle.year}} {{vehicle.vehicle_make}} {{vehicle.vehicle_model}}
                        </h3>
                    </div>
                    <!-- Right Column -->
                    <div class="w-full md:ml-12 mt-8 md:mt-0">
                        Right
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            isVehicleOwner() {
                return this.user.id === this.vehicle.host_id ? true : false;
            }
        },

        data() {
            return {
                loading: false,
                vehicle: null
            }
        },

        methods: {
            async vehicleInfo() {
                this.loading = true;

                this.vehicle = (await axios.get(`/api/vehicle-show/${this.$route.params.id}`)).data.data
            
                this.loading = false;
            }
        },

        created() {
            this.vehicleInfo();
        }
    }
</script>