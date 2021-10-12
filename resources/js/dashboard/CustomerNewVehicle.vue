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
                            <h6 class="text-gray-500 text-xs mt-1">Fill out the form below to describe your vehicle</h6> 
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
                            </div>     
                            <div class="flex flex-col mt-8 md:mt-0 md:w-1/2 md:ml-1">
                                <label for="make" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Model</label>
                                <v-select :options="models"
                                          v-model="newVehicle.model">
                                </v-select>
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
                            </div>
                            <div class="flex flex-col w-2/3 ml-1 pr-2 md:pr-0">
                                <label for="plate" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">License Plate</label>
                                <input type="text"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       v-model="newVehicle.plate">
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
                            </div> 
                            <div class="flex flex-col w-1/3 mr-1 ml-1">
                                <label for="doors" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Doors</label>
                                <input type="number" min="2" max="4"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       v-model="newVehicle.doors">
                            </div> 
                            <div class="flex flex-col w-1/3 ml-1">
                                <label for="price" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Price / Day</label>
                                <input type="number" step="1" pattern="\d+"
                                       class="px-2 py-1 border border-gray-300 text-sm"
                                       @change="priceRound($event)"
                                       v-model="newVehicle.price">
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
                    </div>
                    <!-- Right col -->
                    <div class="w-full md:ml-12 mt-8 md:mt-0">
                        <!-- Header and upload button -->
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-600 text-lg font-boldnosans font-bold text-center">Choose some photos...</h6>
                            <div>
                                <input type="file"
                                    accept="image"
                                    multiple
                                    class="hidden"
                                    ref="fileInput"
                                    @change="imageSelected">
                                <button class="bg-purple-500 hover:bg-purple-400 transition-all 
                                            duration-200 px-2 py-1 text-white font-bold rounded"
                                        @click="pickImage">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Image previews -->
                        <div class="mt-2" v-if="hasUploads">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                                <!-- Image card -->
                                <div v-for="image in previews" :key="image.id">
                                    <div class="w-36 h-36 md:h-24 md:w-24 rounded-sm relative"
                                        :style="{ 'background-image': 'url(' + image.file + ')' }"
                                        style="background-size: cover; background-position: 50% 50%;">
                                        <div class="absolute top-2 right-2 bg-white rounded-full cursor-pointer"
                                            @click="removeImage(image.id)">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mt-6" v-if="hasUploads">
                                <button class="md:w-1/2 text-center bg-purple-500 hover:bg-purple-400 transition-all 
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

    export default {
        components: {
            vSelect
        },

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
                images: [],
                previews: [],
                makes: [],
                models: []
            }
        },

        computed: {
            hasUploads() {
                return this.previews.length > 0;
            }
        },

        methods: {
            pickImage() {
                this.$refs.fileInput.click();
            },

            // Once files are selected, verfiy the extensions are valid, calculated how many image slots
            // are yet unfilled out of the 12 maximum and add them to the images and previews state.
            imageSelected(event) {
                let files = event.target.files;
                let allowedExtensions = /(\jpg|\jpeg|\webp|\bmp|\png|\.gif)$/i;
                let imageCount = this.images.length;
                let maxAllowedCount = 12 - imageCount;

                if (files) {
                    for (let i = 0; i < maxAllowedCount; i++) {
                        if (files[i]) {
                            if (allowedExtensions.exec(files[i].type)) {
                                // Create a unique id
                                let id = "id" + Math.random().toString(16).slice(2);

                                this.images.push({
                                    id: id,
                                    file: files[i]
                                });

                                let reader = new FileReader;

                                reader.readAsDataURL(files[i]);

                                reader.onload = event => {
                                    this.previews.push({
                                        id: id,
                                        file: event.target.result
                                    });
                                };
                            } else {
                                console.log('Invalid image');
                            }
                        }
                    }
                }
            },

            // Remove the selected image from the images and previews state.
            removeImage(id) {
                // Remove the preview
                for (let i = 0; i < this.previews.length; i++) {
                    if (this.previews[i].id === id) {
                        this.previews.splice([i], 1);
                    }
                }

                // Remove the image
                for (let i = 0; i < this.images.length; i++) {
                    if (this.images[i].id === id) {
                        this.images.splice([i], 1);
                    }
                }
            },

            async submit() {
                console.log(this.images);

                const formData = new FormData;

                formData.append('images', this.images);
                formData.append('make', this.newVehicle.make);
                formData.append('model', this.newVehicle.model);
                formData.append('year', this.newVehicle.year);
                formData.append('plate', this.newVehicle.plate);
                formData.append('seats', this.newVehicle.seats);
                formData.append('doors', this.newVehicle.doors);
                formData.append('price', this.newVehicle.price);
                formData.append('description', this.newVehicle.description);

                try {
                    let response = await axios.post('/api/dashboard/create-users-vehicles', formData);

                    console.log(response);
                    
                    // this.$store.dispatch('addNotification', {
                    //     type: 'success',
                    //     message: 'Vehicle added'
                    // });

                    // this.$emit('vehicleAdded');
                } catch(error) {
                    console.log(error.response);
                }
            },

            async getMakes() {
                try {
                    let response = await axios.get('/api/vehicle-make/list');

                    response.data.data.forEach(element => {
                        this.makes.push(element.make);
                    });
                } catch (error) {
                    console.log(error);
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
                    console.log(error);
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