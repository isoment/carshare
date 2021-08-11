<template>
    <div>
        <main-navigation></main-navigation>

        <div v-if="!isLoggedIn">
            <unauthorized></unauthorized>
        </div>

        <div v-else>
            <!-- Banner -->
            <div class="customer-profile-banner h-36 md:h-56 border-b border-gray-200 pb-8">
                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="flex flex-col justify-center items-center pt-10 md:pt-20">
                        <div class="text-center relative">
                            <div class="verify-id-text-wrap relative">
                                <h2 class="font-boldnosans text-4xl md:text-5xl font-bold max-w-2xl mb-4 md:mb-12 z-40">Verify ID</h2>
                            </div>
                            <div class="absolute bg-purple-200 verify-id-bar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="md:relative">
                        <div class="md:absolute mt-2 md:mt-0 md:-top-10 bg-white md:border-2 border-purple-200 rounded-lg p-8 w-full">
                            <h4 class="font-extrabold text-2xl">Drivers License</h4>

                            <!-- License Number -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="license_number" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">License #</label>
                                <input type="text" name="license_number"
                                    class="px-2 py-1 border border-gray-300 text-sm"
                                    v-model="driversLicense.number">
                                <validation-errors :errors="errorFor('license_number')"></validation-errors>
                            </div>

                            <!-- Issuing State -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="state" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Issuing State</label>
                                <v-select :options="statesList"
                                          v-model="driversLicense.state">
                                </v-select>
                                <validation-errors :errors="errorFor('state')"></validation-errors>
                            </div>    

                            <!-- Date Issued -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="date_issued" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Date Issued</label>
                                <date-picker v-model="driversLicense.dateIssued"
                                             color="purple">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <input
                                        class="px-2 py-1 border border-gray-300 text-sm w-full"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                        />
                                    </template>
                                </date-picker>
                                <validation-errors :errors="errorFor('date_issued')"></validation-errors>
                            </div> 

                            <!-- Expiration Date -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="expiration_date" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Expiration Date</label>
                                <date-picker v-model="driversLicense.expirationDate"
                                             color="purple">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <input
                                        class="px-2 py-1 border border-gray-300 text-sm w-full"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                        />
                                    </template>
                                </date-picker>
                                <validation-errors :errors="errorFor('expiration_date')"></validation-errors>
                            </div>   

                            <!-- Birthdate -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="birthdate" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Birthdate</label>
                                <date-picker v-model="driversLicense.birthdate"
                                             color="purple">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <input
                                        class="px-2 py-1 border border-gray-300 text-sm w-full"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                        />
                                    </template>
                                </date-picker>
                                <validation-errors :errors="errorFor('birthdate')"></validation-errors>
                            </div>   

                            <!-- Street -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="street" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Street</label>
                                <input type="text" name="street"
                                    class="px-2 py-1 border border-gray-300 text-sm"
                                    v-model="driversLicense.street">
                                <validation-errors :errors="errorFor('street')"></validation-errors>
                            </div>  

                            <!-- Street -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="city" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">City</label>
                                <input type="text" name="city"
                                    class="px-2 py-1 border border-gray-300 text-sm"
                                    v-model="driversLicense.city">
                                <validation-errors :errors="errorFor('city')"></validation-errors>
                            </div>    

                            <!-- Zipcode -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="zip" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">ZIP</label>
                                <input type="text" name="zip"
                                    class="px-2 py-1 border border-gray-300 text-sm"
                                    v-model="driversLicense.zip">
                                <validation-errors :errors="errorFor('zip')"></validation-errors>
                            </div>                                                                                                   
                        </div>
                    </div>
                    <div class="mt-4 md:mt-6 md:ml-20">
                        <div class="md:mt-6 px-3 md:px-0">
                            <h6 class="font-extrabold text-black">Upload license image</h6>

                            <validation-errors :errors="errorFor('license_image')"></validation-errors>
                            
                            <div class="rounded-md border border-purple-300 mt-6 relative">
                                <div class="">
                                    <div v-if="licenseImagePreview"
                                         class="p-6 flex items-center justify-center">
                                        <img :src="licenseImagePreview" class="h-40">
                                    </div>
                                    <div v-else
                                         class="p-6 flex items-center justify-center">
                                        <svg class="h-40 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input type="file"
                                       accept="image/*"
                                       class="hidden"
                                       ref="fileInput"
                                       @change="imageSelected">
                                <button class="absolute text-center border border-black bg-white font-semibold
                                               hover:text-white hover:bg-purple-400 hover:border-purple-400 transition-all duration-200 
                                               py-1 px-2 top-48 right-6 focus:outline-none tracking-wider"
                                        @click="pickImage">
                                    Select Image
                                </button>
                            </div>

                            <p class="mt-8 text-gray-600 text-sm">
                                Please ensure that the photo of your license is clear and readable. 
                                If it is not this may delay the verification process.
                            </p>

                            <div class="mt-6 w-full text-center relative">
                                <button class="border bg-white border-black py-1 px-3 font-semibold w-full focus:outline-none
                                               hover:border-purple-400 hover:text-purple-400 transition-all duration-200 submit-buton"
                                        @click="submit">
                                    Submit
                                </button>
                                <div class="hidden lg:block absolute bg-purple-200 submit-button-bar"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8" v-if="isLoading">
                <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
            </div>
        </div>
    </div>
</template>

<script>
    import validationErrors from './../shared/mixins/validationErrors';
    import { yearMonthDayNumericHyphen } from './../shared/utils/dateFormats';
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import vSelect from "vue-select";
    import 'vue-select/dist/vue-select.css';

    export default {
        components: {
            Calendar,
            DatePicker,
            vSelect
        },

        mixins: [validationErrors],

        data() {
            return {
                isLoggedIn: this.$store.state.isLoggedIn,
                isLoading: false,
                driversLicense: {
                    number: '',
                    state: '',
                    dateIssued: '',
                    expirationDate: '',
                    birthdate: '',
                    street: '',
                    city: '',
                    zip: ''
                },
                licenseImage: null,
                licenseImagePreview: null,
                statesList: ['Alabama', 'Alaska', 'American Samoa', 'Arizona', 'Arkansas', 'California', 'Colorado', 
                    'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Guam', 'Hawaii', 'Idaho', 
                    'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 
                    'Michigan', 'Minnesota', 'Minor Outlying Islands', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 
                    'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 
                    'Northern Mariana Islands', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Puerto Rico', 'Rhode Island', 
                    'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'U.S. Virgin Islands', 'Utah', 'Vermont', 
                    'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
                ]
            }
        },

        methods: {
            pickImage() {
                this.$refs.fileInput.click();
            },

            imageSelected(event) {
                let file = event.target.files[0];
                let allowedExtensions = /(\jpg|\jpeg|\bmp|\png|\.gif)$/i;

                if (file) {
                    if (allowedExtensions.exec(file.type)) {

                        this.licenseImage = file;

                        let reader = new FileReader;

                        reader.readAsDataURL(this.licenseImage);

                        reader.onload = event => {
                            this.licenseImagePreview = event.target.result;
                        };
                        
                    } else {

                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'Please select a valid image'
                        });
                    
                    }
                }
            },

            async submit() {
                this.validationErrors = null

                const formData = new FormData;

                formData.append('license_image', this.licenseImage);
                formData.append('license_number', this.driversLicense.number);
                formData.append('state', this.driversLicense.state);
                formData.append('date_issued', yearMonthDayNumericHyphen(this.driversLicense.dateIssued));
                formData.append('expiration_date', yearMonthDayNumericHyphen(this.driversLicense.expirationDate));
                formData.append('birthdate', yearMonthDayNumericHyphen(this.driversLicense.birthdate));
                formData.append('street', this.driversLicense.street);
                formData.append('city', this.driversLicense.city);
                formData.append('zip', this.driversLicense.zip);

                try {
                    let response = await axios.post('/api/dashboard/create-drivers-license', formData);

                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: response.data
                    });

                    this.$router.push({ name: "customer-profile" });
                } catch(error) {
                    if (error.response.status === 422) {
                        this.validationErrors = error.response.data.errors;
                    } else {
                        this.$store.dispatch('addNotification', {
                            type: 'error',
                            message: 'Unknown error. Try again later'
                        });
                    }
                }  
            },

            async showLicense() {
                try {
                    let response = await axios.get('/api/dashboard/show-drivers-license');
                    this.driversLicense.number = response.data.data.number;
                    this.driversLicense.city = response.data.data.city;
                    this.driversLicense.birthdate = response.data.data.dob;
                    this.driversLicense.dateIssued = response.data.data.issued;
                    this.driversLicense.expirationDate = response.data.data.expiration;
                    this.driversLicense.state = response.data.data.state;
                    this.driversLicense.street = response.data.data.street;
                    this.driversLicense.zip = response.data.data.zip;
                } catch(error) {}
            }
        },

        created() {
            this.showLicense();
        }
    }
</script>

<style>
    .vs__dropdown-toggle {
        border-radius: 0px;
        padding-top: 1px;
        padding-bottom: 1px;
        color: black;
    }

    .vs__selected {
        margin-top: 0;
        font: 0.875rem;
    }

    .verify-id-text-wrap {
        z-index: 90;
    }

    .verify-id-bar {
        height: 1.65rem;
        width: 8.5rem;
        z-index: 0;
        bottom: 0.5rem;
        left: 1.5rem;
    }

    .submit-button {
        z-index: 90;
    }

    .submit-button-bar {
        height: 2rem;
        width: 24rem;
        z-index: -1;
        bottom: -0.5rem;
        left: 1.5rem;
    }

    @media screen and (min-width: 768px) {
        .verify-id-bar {
            height: 1.95rem;
            width: 12.5rem;
            z-index: 0;
            bottom: 2.5rem;
            left: 1rem;
        }
    }

</style>