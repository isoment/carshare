<template>
    <div>
        <vue-slick-carousel v-bind="carouselSettings"
                            class="mx-6">

            <!-- Previous Arrow -->
            <template #prevArrow="arrowOption">
                <i class="fas fa-chevron-left text-lg custom-arrow">
                    {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
                </i>
            </template>

            <!-- Next Arrow -->
            <template #nextArrow="arrowOption">
                <i class="fas fa-chevron-left text-lg custom-arrow">
                    {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
                </i>
            </template>

            <!-- Top Host Card -->
            <div>
                <div class="rounded-lg shadow-md mx-2 my-5 hover:shadow-lg transition-all transform border 
                            border-gray-100 hover:-translate-y-1 duration-300 p-3">
                    <div class="flex">
                        <div>
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="profile" 
                                class="rounded-full h-20 w-20">
                        </div>
                        <div class="flex flex-col ml-4">
                            <div class="font-bold -mb-1">Test User</div>
                            <div>
                                <i class="fas fa-award text-xs"></i>
                                <span class="text-xs">Top Host</span>
                                <div class="text-xs">
                                    <span>5,431 trips</span>
                                    <span>•</span>
                                    <span>Joined Aug 2015</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex">
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas, repudiandae perferendis ea quas.
                    </div>
                    <div class="italic font-bold text-sm mt-3">
                        Customer Name
                    </div>
                </div>
            </div>

            <!-- Top Host Card -->
            <div>
                <div class="rounded-lg shadow-md mx-2 my-5 hover:shadow-lg transition-all transform border 
                            border-gray-100 hover:-translate-y-1 duration-300 p-3">
                    <div class="flex">
                        <div>
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="profile" 
                                class="rounded-full h-20 w-20">
                        </div>
                        <div class="flex flex-col ml-4">
                            <div class="font-bold -mb-1">Test User</div>
                            <div>
                                <i class="fas fa-award text-xs"></i>
                                <span class="text-xs">Top Host</span>
                                <div class="text-xs">
                                    <span>5,431 trips</span>
                                    <span>•</span>
                                    <span>Joined Aug 2015</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex">
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas, repudiandae perferendis ea quas.
                    </div>
                    <div class="italic font-bold text-sm mt-3">
                        Customer Name
                    </div>
                </div>
            </div>

            <!-- Top Host Card -->
            <div>
                <div class="rounded-lg shadow-md mx-2 my-5 hover:shadow-lg transition-all transform border 
                            border-gray-100 hover:-translate-y-1 duration-300 p-3">
                    <div class="flex">
                        <div>
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="profile" 
                                class="rounded-full h-20 w-20">
                        </div>
                        <div class="flex flex-col ml-4">
                            <div class="font-bold -mb-1">Test User</div>
                            <div>
                                <i class="fas fa-award text-xs"></i>
                                <span class="text-xs">Top Host</span>
                                <div class="text-xs">
                                    <span>5,431 trips</span>
                                    <span>•</span>
                                    <span>Joined Aug 2015</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex">
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas, repudiandae perferendis ea quas.
                    </div>
                    <div class="italic font-bold text-sm mt-3">
                        Customer Name
                    </div>
                </div>
            </div>

            <!-- Top Host Card -->
            <div>
                <div class="rounded-lg shadow-md mx-2 my-5 hover:shadow-lg transition-all transform border 
                            border-gray-100 hover:-translate-y-1 duration-300 p-3">
                    <div class="flex">
                        <div>
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="profile" 
                                class="rounded-full h-20 w-20">
                        </div>
                        <div class="flex flex-col ml-4">
                            <div class="font-bold -mb-1">Test User</div>
                            <div>
                                <i class="fas fa-award text-xs"></i>
                                <span class="text-xs">Top Host</span>
                                <div class="text-xs">
                                    <span>5,431 trips</span>
                                    <span>•</span>
                                    <span>Joined Aug 2015</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex">
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                            <i class="fas fa-star text-sm text-purple-500"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptas, repudiandae perferendis ea quas.
                    </div>
                    <div class="italic font-bold text-sm mt-3">
                        Customer Name
                    </div>
                </div>
            </div>


        </vue-slick-carousel>
    </div>
</template>

<script>
    import VueSlickCarousel from 'vue-slick-carousel';
    import 'vue-slick-carousel/dist/vue-slick-carousel.css';
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css';

    export default {
        components: {
            VueSlickCarousel
        },

        data() {
            return {
                carouselSettings: {
                    "dots": false,
                    "infinite": true,
                    "initialSlide": 2,
                    "slidesToShow": 3,
                    "slidesToScroll": 1,
                    "swipeToSlide": true,
                    "speed": 200,
                    "responsive": [
                        {
                            "breakpoint": 1000,
                            "settings": {
                                "arrows": true,
                                "dots": false,
                                "slidesToShow": 2,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        },
                        {
                            "breakpoint": 650,
                            "settings": {
                                "arrows": false,
                                "dots": false,
                                "slidesToShow": 1,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        }
                    ]
                },
            }
        }
    }
</script>

<style scoped>
    .slick-next::before {
        color: #9f7aea;
    }

    .slick-prev::before{
        color: #9f7aea;
    }
</style>