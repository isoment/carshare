<template>
    <div>
        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div v-if="vehicle">

            <!-- Display unauthorized if not logged in -->
            <div v-if="!isLoggedIn">
                <error :message="'Not Authorized'"></error>
            </div>

            <!-- Error if user is not owner -->
            <div v-else-if="!isVehicleOwner">
                <error :message="'This is not your vehicle'"
                       :routeName="'customer-profile'"
                       :routeDesc="'profile page'">
                </error>
            </div>

            <div v-else>
                <!-- Loading spinner -->
                <!-- <div class="text-center mt-8"
                    v-if="loading">
                    <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
                </div> -->

                <!-- Banner -->
                <div class="customer-profile-banner h-36 md:h-40 border-b border-gray-200 pb-8">
                    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                        <div class="relative">
                            <div class="absolute right-2 lg:right-8 top-16 md:top-20">
                                <div>
                                    <router-link class="bg-white px-4 py-2 text-gray-800 border-2 
                                                       border-gray-800 font-bold mr-2"
                                                 :to="{ name: 'customer-vehicles' }">
                                        Manage Vehicles
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mt-4">
                    <div class="relative">
                        <div class="absolute -top-20 mb-12">
                            <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                                <h3 class="text-lg font-boldnosans font-bold">Edit Vehicle</h3>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 mt-12">
                        <div>
                            <!-- Header info -->
                            <div>
                                <h3 class="text-2xl md:text-3xl font-extrabold">
                                    {{vehicle.year}} {{vehicle.vehicle_make}} {{vehicle.vehicle_model}}
                                </h3>
                                <h6 class="text-gray-500 text-xs mt-1">Modify your vehicle details below...</h6>
                            </div>

                            <!-- Status and Price -->
                            <div class="flex mt-8">
                                <div class="flex flex-col w-1/2 mr-2">
                                    <label for="year" 
                                        class="text-gray-400 text-xs font-bold uppercase 
                                                tracking-wider mb-2">Vehcile Status</label>
                                    <button class="w-full text-center transition-all text-sm focus:outline-none
                                                   duration-200 px-4 text-white edit-vehicle-status-button"
                                            :class="{
                                                'bg-red-400 hover:bg-red-300': !active,
                                                'bg-green-400 hover:bg-green-300': active
                                            }"
                                            @click="toggleVehicleStatus">
                                        {{vehicleStatusText}}
                                    </button>
                                </div>
                                <div class="flex flex-col w-1/2 mr-1">
                                    <label for="year" 
                                        class="text-gray-400 text-xs font-bold uppercase 
                                                tracking-wider mb-2">Price / Day</label>
                                    <input type="number" step="1" pattern="\d+"
                                           class="px-2 py-1 border border-gray-300 text-sm"
                                           @change="priceRound($event)"
                                           v-model="price">
                                </div>  
                            </div>

                            <!-- Description -->
                            <div class="mt-8">
                                <div class="flex flex-col">
                                    <label for="description"
                                            class="text-gray-400 text-xs font-bold uppercase 
                                                    tracking-wider mb-2">Description</label>
                                    <textarea name="description" rows="10"
                                            class="px-2 py-1 border border-gray-300 text-sm"
                                            v-model="description"></textarea>
                                </div>
                            </div>

                            <!-- Save -->
                            <div class="mt-3">
                                <button class="w-full text-center bg-purple-500 hover:bg-purple-400 transition-all 
                                               duration-200 px-4 py-2 text-white font-bold"
                                        @click="submit">Save changes</button>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="w-full md:ml-12 mt-8 md:mt-0">
                            <!-- Header and upload button -->
                            <div class="flex items-center justify-between">
                                <div class="mb-2">
                                    <h4 class="text-gray-600 text-lg font-boldnosans font-bold">
                                        Choose some photos...
                                    </h4>
                                    <h6 class="text-gray-500 text-xs mt-1">
                                        Click an image to set it as the featured image
                                    </h6>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">

                                    <!-- Existing Images -->
                                    <div v-for="imageUrl in vehicle.vehicle_images" :key="imageUrl.id">
                                        <div class="w-full h-36 md:h-24 md:w-full rounded-sm relative"
                                            :style="{ 'background-image': 'url(' + imageUrl + ')' }"
                                            style="background-size: cover; background-position: 50% 50%;">
                                            <div class="absolute top-2 right-2 bg-white rounded-full cursor-pointer"
                                                @click="removeExistingImage(imageUrl)">
                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                            <!-- <div class="absolute top-2 left-2 bg-white rounded-full"
                                                v-if="image.id === featuredId">
                                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>                                        
                                            </div> -->
                                        </div>
                                    </div>

                                    <!-- Add image -->
                                    <!-- <div v-if="addImage">
                                        <input type="file"
                                            accept="image"
                                            multiple
                                            class="hidden"
                                            ref="fileInput"
                                            @change="imageSelected">
                                        <button class="w-full h-36 md:h-24 md:w-full rounded-sm flex items-center justify-center
                                                    bg-gray-50 border border-gray-300 text-gray-300"
                                                @click="pickImage">
                                            <i class="fas fa-plus text-xl"></i>
                                        </button>
                                    </div> -->

                                </div>
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

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            isVehicleOwner() {
                return this.user.id === this.vehicle.host_id ? true : false;
            },

            vehicleStatusText() {
                return this.active ? 'Vehicle active' : 'Vehicle inactive';
            }
        },

        data() {
            return {
                description: null,
                price: null,
                active: null,
                loading: false,
                vehicle: null
            }
        },

        methods: {
            async vehicleInfo() {
                this.loading = true;

                this.vehicle = (await axios.get(`/api/vehicle-show/${this.$route.params.id}`)).data.data
            
                this.active = this.vehicle.active;

                this.loading = false;
            },

            async removeExistingImage(imageUrl) {
                try {
                    await axios.delete(
                        '/api/dashboard/delete-vehicle-image',
                        { data: { image: imageUrl } }
                    );

                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: 'Image removed'
                    });

                    this.vehicleInfo();
                } catch (error) {
                    if (error.response.status === 403) {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: error.response.data
                        });
                    }

                    if (error.response.status === 422) {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'Invalid image'
                        });
                    }
                }
            },

            toggleVehicleStatus() {
                this.active = !this.active;
            },

            priceRound(event) {
                this.price = Math.round(event.target.value);
            },

            submit() {

            }
        },

        created() {
            this.vehicleInfo();
        }
    }
</script>

<style scoped>
    .edit-vehicle-status-button {
        padding: 5px 0 5px 0;
    }
</style>