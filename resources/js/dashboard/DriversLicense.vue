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
                        <div class="md:absolute mt-2 md:mt-0 md:-top-10 bg-white border-2 border-purple-200 rounded-lg p-8 w-full">
                            <h4 class="font-extrabold text-2xl">Drivers License</h4>

                            <!-- License Number -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="license_number" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">License #</label>
                                <input type="text" name="license_number"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>

                            <!-- Issuing State -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="state" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Issuing State</label>
                                <input type="text" name="state"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>    

                            <!-- Date Issued -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="date_issued" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Date Issued</label>
                                <input type="text" name="date_issued"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div> 

                            <!-- Expiration Date -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="expiration_date" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Expiration Date</label>
                                <input type="text" name="expiration_date"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>   

                            <!-- Birthdate -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="birthdate" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Birthdate</label>
                                <input type="text" name="birthdate"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>   

                            <!-- Street -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="street" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">Street</label>
                                <input type="text" name="street"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>  

                            <!-- Date Issued -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="city" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">City</label>
                                <input type="text" name="city"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>     

                            <!-- Zipcode -->
                            <div class="flex flex-col mt-4 mb-3">
                                <label for="zip" 
                                    class="text-gray-400 text-xs font-bold uppercase 
                                            tracking-wider mb-2">ZIP</label>
                                <input type="text" name="zip"
                                    class="px-2 py-1 border border-gray-300 text-sm">
                            </div>                                                                                                   
                        </div>
                    </div>
                    <div class="mt-4 md:mt-6 md:ml-20">
                        <div class="text-center mt-12">
                            <h6 class="font-extrabold text-gray-500">Please provide your license information and image for verification.</h6>
                            
                            <div class="rounded-md border-2 border-purple-300 mt-8 relative">
                                <div class="">
                                    <div v-if="licenseImagePreview"
                                         class="p-6 flex items-center justify-center">
                                        <img :src="licenseImagePreview" class="max-h-40">
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
                                <button class="absolute text-center text-purple-300 border-2 border-purple-300 bg-white
                                               hover:text-white hover:bg-purple-400 hover:border-purple-400 transition-all duration-200 
                                               font-bold py-1 px-2 top-48 right-6 focus:outline-none tracking-wider"
                                        @click="pickImage">
                                    Select Image
                                </button>
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
    export default {

        data() {
            return {
                isLoggedIn: this.$store.state.isLoggedIn,
                isLoading: false,
                driversLicense: {
                    number: null,
                    state: null,
                    dateIssued: null,
                    expirationDate: null,
                    birthdate: null,
                    street: null,
                    city: null,
                    zip: null
                },
                licenseImage: null,
                licenseImagePreview: null
            }
        },

        methods: {
            pickImage() {
                this.$refs.fileInput.click();
            },

            imageSelected(event) {
                if (event.target.files[0]) {
                    this.licenseImage = event.target.files[0];

                    let reader = new FileReader;
                    reader.readAsDataURL(this.licenseImage);
                    reader.onload = event => {
                        this.licenseImagePreview = event.target.result;
                    };
                }
            }
        }
        
    }
</script>

<style>

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