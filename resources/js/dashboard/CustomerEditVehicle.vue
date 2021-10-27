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

            <div v-else>
                <!-- Loading spinner -->
                <div class="text-center mt-8"
                    v-if="loading">
                    <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
                </div>

                <div>
                    Edit vehicle {{$route.params.id}}
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