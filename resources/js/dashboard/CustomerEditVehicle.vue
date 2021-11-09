<template>
    <div>
        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div v-if="vehicleNotFound">
            <error :message="'Vehicle not found'"
                    :routeName="'customer-vehicles'"
                    :routeDesc="'my vehicles'"></error>
        </div>

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

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="relative">
                        <div class="absolute -top-20 mb-12">
                            <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                                <h3 class="text-lg font-boldnosans font-bold">Edit Vehicle</h3>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 mt-12">

                        <!-- Left Columm -->
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
                                    <button class="w-full text-center transition-all text-sm font-semibold focus:outline-none
                                                   duration-200 px-4 border-2 edit-vehicle-status-button tracking-wider"
                                            :class="{
                                                'border-gray-600 text-gray-600': !active,
                                                'border-purple-400 text-purple-400': active
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
                            <validation-errors :errors="errorFor('active')"></validation-errors>
                            <validation-errors :errors="errorFor('price')"></validation-errors>

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
                                <validation-errors :errors="errorFor('description')"></validation-errors>
                            </div>

                            <!-- Save -->
                            <div class="mt-3">
                                <button class="w-full text-center bg-gray-300 px-4 py-2 text-white font-bold
                                              flex items-center justify-center"
                                        :disabled="submittingForm"
                                        :class="{ 'bg-purple-500 hover:bg-purple-400 transition-all duration-200' : !submittingForm }"
                                        @click="submit">
                                    <span>Save changes</span>
                                    <span v-if="submittingForm">
                                        <i class="fas fa-spinner fa-spin text-white text-sm ml-2"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="md:ml-12 mt-8 md:mt-0">
                            <!-- Featured Image -->
                            <div>
                                <div class="mb-2">
                                    <h4 class="text-gray-600 text-lg font-boldnosans font-bold">
                                        Featured image
                                    </h4>
                                    <div class="w-full">
                                        <img class="w-full" :src="existingFeaturedImage">
                                    </div>
                                </div>
                            </div>

                            <!-- Header and upload button -->
                            <div class="flex items-center justify-between">
                                <div class="mb-2">
                                    <h4 class="text-gray-600 text-lg font-boldnosans font-bold">
                                        Choose some photos...
                                    </h4>
                                    <h6 class="text-gray-500 text-xs mt-1">
                                        Click an image to set it as the new featured image
                                    </h6>
                                </div>
                            </div>

                            <div class="mt-2">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">

                                    <!-- Existing Images -->
                                    <div v-for="imageUrl in vehicle.vehicle_images" :key="imageUrl.id">
                                        <div class="w-full h-36 md:h-24 md:w-full rounded-sm relative"
                                            :style="{ 'background-image': 'url(' + imageUrl + ')' }"
                                            style="background-size: cover; background-position: 50% 50%;"
                                            @click.self="setFeaturedImage(imageUrl)">
                                            <div class="absolute top-2 right-2 bg-white rounded-full cursor-pointer"
                                                @click="removeExistingImage(imageUrl)">
                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                            <div class="absolute top-2 left-2 bg-white rounded-full"
                                                v-if="imageUrl === featuredId">
                                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>                                        
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New images -->
                                    <div v-for="image in previews" :key="image.id">
                                        <div class="w-full h-36 md:h-24 md:w-full rounded-sm relative"
                                            :style="{ 'background-image': 'url(' + image.file + ')' }"
                                            style="background-size: cover; background-position: 50% 50%;"
                                            @click.self="setFeaturedImage(image.id)">
                                            <div class="absolute top-2 right-2 bg-white rounded-full cursor-pointer"
                                                @click="removeImage(image.id)">
                                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                            <div class="absolute top-2 left-2 bg-white rounded-full"
                                                v-if="image.id === featuredId">
                                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>                                        
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add image -->
                                    <div>
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
                                    </div>

                                </div>
                                <validation-errors :errors="errorFor('images')"></validation-errors>
                                <validation-errors :errors="errorFor('featured_id')"></validation-errors>
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
    import validationErrors from '../shared/mixins/validationErrors';
    import imageSelecting from './../shared/mixins/imageSelecting';

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

        mixins: [validationErrors, imageSelecting],

        data() {
            return {
                submittingForm: false,
                vehicleNotFound: null,
                description: null,
                price: null,
                active: null,
                loading: false,
                vehicle: null,
                existingFeaturedImage: null,
                featuredImage: '',
                featuredId: '',
            }
        },

        methods: {
            setFeaturedImage(identifier) {
                let mergedArrays = this.images.concat(this.vehicle.vehicle_images);

                for (let i = 0; i < mergedArrays.length; i++) {
                    // If the featured image selected is one of the new images
                    if (mergedArrays[i].id === identifier) {
                        this.featuredImage = mergedArrays[i];
                        this.featuredId = mergedArrays[i].id;
                    }

                    // If the featured image is one of the previous uploaded images
                    if (mergedArrays[i] === identifier) {
                        this.featuredImage = mergedArrays[i];
                        this.featuredId = mergedArrays[i];
                    }
                }
            },

            async vehicleInfo() {
                this.loading = true;

                try {
                    this.vehicle = (await axios.get(`/api/vehicle-show/${this.$route.params.id}`)).data.data
                    console.log(this.vehicle);
                } catch (error) {
                    if (error.response.status === 404) {
                        this.vehicleNotFound = true
                    }
                }

                this.active = this.vehicle.active === 1 ? true : false;
                this.price = this.vehicle.price;
                this.description = this.vehicle.description;
                this.existingFeaturedImage = this.vehicle.featured_image;

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

                    if (this.featuredImage === imageUrl) {
                        this.featuredImage = '';
                        this.featuredId = '';
                    }
                } catch (error) {
                    if (error.response.status === 403 || error.response.status === 404) {
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

                this.vehicleInfo();
            },

            toggleVehicleStatus() {
                this.active = !this.active;
            },

            priceRound(event) {
                this.price = Math.round(event.target.value);
            },

            async submit() {
                this.validationErrors = null;
                this.submittingForm = true;

                const formData = new FormData;

                // Loop over images and append to formData
                for (let i = 0; i < this.images.length; i++) {
                    formData.append(`images[${[i]}]`, this.images[i].file, this.images[i].id);
                }

                formData.append('featured_id', this.featuredId);
                formData.append('description', this.description);
                formData.append('price', this.price);
                formData.append('active', this.active);

                try {
                    await axios.post(`/api/dashboard/update-users-vehicles/${this.vehicle.id}`, formData);

                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: 'Vehicle updated successfully'
                    });

                    this.vehicleInfo();

                    this.images = [];
                    this.previews = [];
                    this.featuredImage = '';
                    this.featuredId = '';
                } catch (error) {
                    if (error.response.status === 422) {
                        this.validationErrors = error.response.data.errors;
                    } else if (error.response.status === 403 || error.response.status === 404) {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: error.response.data
                        });
                    } else {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'Error, try again later'
                        });
                    }
                }

                this.submittingForm = false;
            }
        },

        created() {
            this.vehicleInfo();
        }
    }
</script>

<style scoped>
    .edit-vehicle-status-button {
        padding: 3px 0 3px 0;
    }
</style>