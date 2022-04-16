<template>
    <div>
        <main-navigation></main-navigation>

        <div>
            <!-- Display unauthorized if not logged in -->
            <div v-if="!isLoggedIn">
                <error :message="'Not Authorized'"></error>
            </div>

            <!-- If user is not a host display an error -->
            <div v-else-if="!isHost">
                <div class="max-w-3xl mx-auto px-2 sm:px-6 lg:px-8 mb-6 mt-6">
                    <div class="text-white shadow-md pl-4 pr-3 py-3 rounded-md text-sm my-2 bg-red-400">
                        Please <span class="font-bold"><router-link :to="{ name: 'drivers-license' }">verify</router-link></span> identity before hosting drivers.
                    </div>
                </div>
            </div>
            
            <!-- Display shared vehicle section -->
            <div v-else>
                <div class="customer-profile-banner h-36 md:h-40 border-b border-gray-200 pb-8">
                    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                        <div class="relative">
                            <div class="absolute right-2 lg:right-8 top-12 md:top-20">
                                <div v-if="!addNewCar">
                                    <a href="#" 
                                       class="bg-white px-4 py-2 text-gray-800 border-2 border-gray-800 
                                              font-bold mr-2"
                                       @click="addNewCar = true">
                                        New Car
                                    </a>
                                </div>
                                <div v-else>
                                    <a href="#"
                                       class="bg-white px-6 py-2 text-gray-800 border-2 border-gray-800 
                                              font-bold mr-2"
                                       @click="switchToManageMode">
                                        My Cars
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <customer-new-vehicle v-if="addNewCar" 
                                          @vehicleAdded="addNewCar = false">
                    </customer-new-vehicle>
                    <customer-manage-vehicles v-else></customer-manage-vehicles>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import CustomerManageVehicles from './CustomerManageVehicles.vue';
    import CustomerNewVehicle from './CustomerNewVehicle.vue';

    export default {
        components: {
            CustomerManageVehicles,
            CustomerNewVehicle
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            isHost() {
                return this.user.host === 1;
            },
        },

        methods: {
            switchToManageMode() {
                if (confirm('All changes will be lost, continue?')) {
                    this.addNewCar = false;
                }
            }
        },

        data() {
            return {
                addNewCar: false
            }
        }
    }
</script>