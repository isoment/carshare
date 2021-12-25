<template>
    <div class="mt-2">
        <div class="flex flex-row">
            <div class="p-4 border rounded-md w-1/3 text-center flex flex-col items-center mr-1">
                <h6 class="font-boldnosans font-semibold mb-2">As Renter</h6>
                <div>
                    <label for="booking-stat-toggle" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" id="booking-stat-toggle" class="sr-only">
                            <div class="booking-stat-toggle-bg block bg-gray-200 w-14 h-8 rounded-full"></div>
                            <div class="booking-stat-toggle-dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="p-4 border rounded-md w-1/3 text-center flex flex-col items-center mr-1">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Total</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-2xl text-gray-800 font-bold font-boldnosans header-rating">
                            142
                        </h5>
                    </div>
                    <div class="h-8 w-8 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-car text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-4 border rounded-md w-1/3 text-center flex flex-col items-center ml-1">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Cancelled</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-2xl text-gray-800 font-bold font-boldnosans header-rating">
                            8
                        </h5>
                    </div>
                    <div class="h-8 w-8 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-trash-alt text-purple-400 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style>
    input:checked ~ .booking-stat-toggle-dot {
        transform: translateX(100%);
    }

    input:checked ~ .booking-stat-toggle-bg {
        background-color: rgb(167, 134, 255);
    }
</style>