<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">New Vehicles</h3>
                </div>
            </div>

        <div class="pt-12">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Left Col -->
                    <div>
                        <div class="mb-8">
                            <h3 class="text-2xl md:text-3xl font-extrabold">Tell us about your vehicle...</h3> 
                        </div>
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
                            <div class="flex flex-col mt-2 md:mt-0 md:w-1/2 md:ml-1">
                                <label for="make" 
                                       class="text-gray-400 text-xs font-bold uppercase 
                                              tracking-wider mb-2">Model</label>
                                <v-select :options="models"
                                          v-model="newVehicle.model">
                                </v-select>
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
                    make: null,
                    model: null
                },
                makes: [],
                models: []
            }
        },

        methods: {
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
            }
        },

        created() {
            this.getMakes();
        }
    }
</script>