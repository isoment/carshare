<template>
    <div>

        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <!-- Vehicle Image Slider -->
        <div class="vehicle-image-slider"
            style="background-size: cover; 
                   background-position: 50% 50%;
                   background-image: url(/storage/vehicle-seeder-img/honda-3219ur1hfo9eq.jpg)">
        </div>

        <div class="mt-6 max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3">
                <div class="col-span-2 grid grid-cols-4 mr-4">
                    <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                        The car
                    </div>
                    <div class="col-span-3 flex flex-col mx-2">
                        <h2 class="font-bold text-4xl font-boldnosans">Tesla Model S 2016</h2>
                        <div class="my-2">
                            <span class="font-boldnosans font-bold text-xl">4.95</span>
                            <span><i class="fas fa-star text-purple-500 text-md"></i></span>
                            <span class="font-light text-sm">(29 trips)</span>
                        </div>
                        <div class="grid grid-cols-2 text-lg mt-4">
                            <div class="flex items-center">
                                <div class="mr-1">
                                    <img src="/img/safety-seat.svg" alt="" class="h-7 w-7">
                                </div>
                                <div>
                                    4 Seats
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="mr-1">
                                    <img src="/img/car-door.svg" alt="" class="h-7 w-7">
                                </div>
                                <div class="ml-1">
                                    4 Doors
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-100 text-center">
                    Check Availability Component
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        
    }
</script>