<template>
    <div>
        <main-navigation></main-navigation>
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
            <div class="mt-8">
                <div class="flex items-center justify-end mb-4">
                    <span class="mr-2 font-bold font-boldnosans text-gray-700">As Host:</span>
                    <label for="booking-stat-toggle" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" 
                                id="booking-stat-toggle" 
                                class="sr-only"
                                v-model="toggle">
                            <div class="booking-stat-toggle-bg block bg-gray-200 w-14 h-7 rounded-full"></div>
                            <div class="booking-stat-toggle-dot absolute left-2 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
                <div class="flex justify-between">
                    <div class="bg-green-100 w-full mr-2 rounded-md py-8 px-4">
                        Abc
                    </div>
                    <div class="bg-red-200 w-full ml-2 mr-2 rounded-md py-8 px-4">
                        Bcd
                    </div>
                    <div class="bg-blue-200 w-full ml-2 rounded-md py-8 px-4">
                        Cde
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                toggle: false
            }
        }
    }
</script>

<style scoped>
    input:checked ~ .booking-stat-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .booking-stat-toggle-bg {
        background-color: rgb(167, 134, 255);
    }
</style>