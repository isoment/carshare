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

        <div class="my-10 max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="grid grid-cols-3">
                <div class="col-span-2 mr-4">

                    <div class="grid grid-cols-4">
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

                    <div class="grid grid-cols-4 mt-12">
                        <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                            Hosted by
                        </div>
                        <div class="col-span-3 mx-2">
                            <div class="flex items-center">
                                <div class="relative">
                                    <img src="/img/avatar-female.jpeg" alt="avatar" 
                                         class="h-20 w-20 rounded-full">
                                    <div class="absolute bg-white shadow-lg rounded-full border border-gray-50 px-4
                                                flex top-16">
                                        <span class="mr-1 font-semibold">4.8</span>
                                        <span><i class="fas fa-star text-purple-500 text-md"></i></span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-boldnosans font-bold text-lg">Host Name</h3>
                                    <div class="text-sm">
                                        <span>1,051 trips</span> • <span>Joined Oct, 2018</span>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>

                    <div class="grid grid-cols-4 mt-12">
                        <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                            Description
                        </div>
                        <div class="col-span-3 mx-2">
                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias aut, repellat ipsum facere voluptate dicta obcaecati deserunt nobis suscipit eaque?
                                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias aut, repellat ipsum facere voluptate dicta obcaecati deserunt nobis suscipit eaque?
                                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias aut, repellat ipsum facere voluptate dicta obcaecati deserunt nobis suscipit eaque?
                            </p>
                        </div>                        
                    </div>

                </div>

                <div class="font-boldnosans">
                    <div>
                        <span class="font-bold text-xl font-boldnosans">$185</span>
                        <span>/</span>
                        <span>day</span>
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