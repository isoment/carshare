<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">New Vehicle</h3>
                </div>
            </div>

            <div class="pt-12">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Left Col -->
                    <div>
                        <div class="mb-8">
                            <h3 class="text-2xl md:text-3xl font-extrabold">Tell us about your vehicle...</h3>
                            <h6 class="text-gray-500 text-xs mt-1">Fill out the form below to describe your vehicle. 
                                <span class="text-red-500">NOTE: You must active this vehicle in manage vehicles before it is
                                    available to renters.
                                </span>
                            </h6>
                        </div>
                        <!-- Make and model -->
                        <div class="md:flex">
                            <div class="flex flex-col md:w-1/2 md:mr-1">
                                <label for="make" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Make</label>
                                <v-select :options="makes"
                                            v-model="newVehicle.make"
                                            @input="getModels">
                                </v-select>
                                <validation-errors :errors="errorFor('make')"></validation-errors> 
                            </div>
                            <div class="flex flex-col mt-8 md:mt-0 md:w-1/2 md:ml-1">
                                <label for="make" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Model</label>
                                <v-select :options="models"
                                          v-model="newVehicle.model">
                                </v-select>
                                <validation-errors :errors="errorFor('model')"></validation-errors>
                            </div>   
                        </div>
                        <!-- Year and plate -->
                        <div class="flex mt-8">
                            <div class="flex flex-col w-1/3 mr-1">
                                <label for="year" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Year</label>
                                <input type="text"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       v-model="newVehicle.year">
                                <validation-errors :errors="errorFor('year')"></validation-errors>
                            </div>
                            <div class="flex flex-col w-2/3 ml-1 pr-2 md:pr-0">
                                <label for="plate" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">License Plate</label>
                                <input type="text"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       v-model="newVehicle.plate">
                                <validation-errors :errors="errorFor('plate')"></validation-errors>
                            </div>      
                        </div>
                        <!-- Seats, doors and price -->
                        <div class="flex mt-8">
                            <div class="flex flex-col w-1/3 mr-1">
                                <label for="seats" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Seats</label>
                                <input type="number" max="10"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       v-model="newVehicle.seats">
                                <validation-errors :errors="errorFor('seats')"></validation-errors>
                            </div> 
                            <div class="flex flex-col w-1/3 mr-1 ml-1">
                                <label for="doors" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Doors</label>
                                <input type="number" min="2" max="4"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       v-model="newVehicle.doors">
                                <validation-errors :errors="errorFor('doors')"></validation-errors>
                            </div> 
                            <div class="flex flex-col w-1/3 ml-1">
                                <label for="price" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Price / Day</label>
                                <input type="number" step="1" pattern="\d+"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       @change="priceRound($event)"
                                       v-model="newVehicle.price">
                                <validation-errors :errors="errorFor('price')"></validation-errors>
                            </div> 
                        </div>
                        <!-- Vehicle description -->
                        <div class="mt-8">
                            <div class="flex flex-col">
                                <label for="description"
                                        class="text-gray-400 text-xs font-bold uppercase 
                                                tracking-wider mb-2">Description</label>
                                <textarea name="description" rows="10"
                                        class="px-2 py-1 border border-gray-300 text-sm"
                                        v-model="newVehicle.description"></textarea>
                            </div>
                        </div>
                        <validation-errors :errors="errorFor('description')"></validation-errors>
                    </div>
                    <!-- Right col -->
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
                        <!-- Image previews -->
                        <div class="mt-2">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                                <!-- Image card -->
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
                                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>                                        </div>
                                    </div>
                                </div>
                                <!-- Add image -->
                                <div v-if="addImage">
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
                        </div>
                        <validation-errors :errors="errorFor('images')"></validation-errors>
                        <validation-errors :errors="errorFor('featured_id')"></validation-errors>
                        <div>
                            <div class="mt-6 text-right" v-if="hasUploads">
                                <button class="w-full text-center bg-purple-500 hover:bg-purple-400 transition-all 
                                            duration-200 px-4 py-2 text-white font-bold"
                                        @click="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';
    import imageSelecting from './../shared/mixins/imageSelecting';
    import validationErrors from './../shared/mixins/validationErrors';

    export default {
        components: {
            vSelect
        },

        mixins: [validationErrors, imageSelecting],

        data() {
            return {
                newVehicle: {
                    make: '',
                    model: '',
                    year: '',
                    plate: '',
                    seats: '',
                    doors: '',
                    price: '',
                    description: ''
                },
                featuredImage: '',
                featuredId: '',
                makes: [],
                models: []
            }
        },

        computed: {
            hasUploads() {
                return this.previews.length > 0;
            },

            addImage() {
                return this.images.length < 12;
            }
        },

        methods: {
            // Set the feauredImage state and the featuredId state used to apply a styling class
            setFeaturedImage(id) {
                for (let i = 0; i < this.images.length; i++) {
                    if (this.images[i].id === id) {
                        this.featuredImage = this.images[i];
                        this.featuredId = this.images[i].id;
                    }
                }
            },

            async submit() {
                this.validationErrors = null;

                const formData = new FormData;

                // Loop over images and append to formData
                for (let i = 0; i < this.images.length; i++) {
                    formData.append(`images[${[i]}]`, this.images[i].file, this.images[i].id);
                }

                formData.append('featured_id', this.featuredId);
                formData.append('make', this.newVehicle.make);
                formData.append('model', this.newVehicle.model);
                formData.append('year', this.newVehicle.year);
                formData.append('plate', this.newVehicle.plate);
                formData.append('seats', this.newVehicle.seats);
                formData.append('doors', this.newVehicle.doors);
                formData.append('price', this.newVehicle.price);
                formData.append('description', this.newVehicle.description);

                try {
                    await axios.post('/api/dashboard/create-users-vehicles', formData);
                    
                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: 'Vehicle added'
                    });

                    this.$emit('vehicleAdded');
                } catch(error) {
                    if (error.response.status === 422) {
                        this.validationErrors = error.response.data.errors;
                    } else {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'Error, try again later'
                        });
                    }
                }
            },

            async getMakes() {
                try {
                    let response = await axios.get('/api/vehicle-make/list');

                    response.data.data.forEach(element => {
                        this.makes.push(element.make);
                    });
                } catch (error) {
                    console.log('Error getting makes');
                }

            },

            async getModels() {
                try {
                    this.newVehicle.model = '';

                    let response = await axios.get('/api/vehicle-models/list', {
                        params: {
                            make: this.newVehicle.make
                        }
                    });

                    this.models = response.data;
                } catch (error) {
                    console.log('Error getting models');
                }
            },

            priceRound(event) {
                this.newVehicle.price = Math.round(event.target.value);
            }
        },

        created() {
            this.getMakes();
        }
    }
</script>