<template>
    <div>
        <vue-slick-carousel v-bind="carouselSettings" class="mx-6">
            
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

            <!-- Card -->
            <div>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-md mx-2 my-3 hover:shadow-lg
                            transition-all transform hover:-translate-y-1 duration-300 hover:text-purple-500">
                    <img class="w-full" src="/img/card-top.jpg" alt="Sunset in the mountains">
                    <div class="px-6 py-3 text-center">
                        <div class="font-bold mb-2 font-boldnosans">Toyota</div>
                    </div>
                </div>
            </div>
            <!-- Card -->
            <div>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-md mx-2 my-3 hover:shadow-lg
                            transition-all transform hover:-translate-y-1 duration-300 hover:text-purple-500">
                    <img class="w-full" src="/img/card-top.jpg" alt="Sunset in the mountains">
                    <div class="px-6 py-3 text-center">
                        <div class="font-bold mb-2 font-boldnosans">Toyota</div>
                    </div>
                </div>
            </div>
            <!-- Card -->
            <div>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-md mx-2 my-3 hover:shadow-lg
                            transition-all transform hover:-translate-y-1 duration-300 hover:text-purple-500">
                    <img class="w-full" src="/img/card-top.jpg" alt="Sunset in the mountains">
                    <div class="px-6 py-3 text-center">
                        <div class="font-bold mb-2 font-boldnosans">Toyota</div>
                    </div>
                </div>
            </div>      
            <!-- Card -->
            <div>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-md mx-2 my-3 hover:shadow-lg
                            transition-all transform hover:-translate-y-1 duration-300 hover:text-purple-500">
                    <img class="w-full" src="/img/card-top.jpg" alt="Sunset in the mountains">
                    <div class="px-6 py-3 text-center">
                        <div class="font-bold mb-2 font-boldnosans">Toyota</div>
                    </div>
                </div>
            </div>      
            <!-- Card -->
            <div>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-md mx-2 my-3 hover:shadow-lg
                            transition-all transform hover:-translate-y-1 duration-300 hover:text-purple-500">
                    <img class="w-full" src="/img/card-top.jpg" alt="Sunset in the mountains">
                    <div class="px-6 py-3 text-center">
                        <div class="font-bold mb-2 font-boldnosans">Toyota</div>
                    </div>
                </div>
            </div>      
            <!-- Card -->
            <div>
                <div class="max-w-sm rounded-lg overflow-hidden shadow-md mx-2 my-3 hover:shadow-lg
                            transition-all transform hover:-translate-y-1 duration-300 hover:text-purple-500">
                    <img class="w-full" src="/img/card-top.jpg" alt="Sunset in the mountains">
                    <div class="px-6 py-3 text-center">
                        <div class="font-bold mb-2 font-boldnosans">Toyota</div>
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
                    "slidesToShow": 5,
                    "slidesToScroll": 1,
                    "swipeToSlide": true,
                    "speed": 200,
                    "responsive": [
                        {
                            "breakpoint": 1200,
                            "settings": {
                                "arrows": true,
                                "dots": false,
                                "slidesToShow": 4,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        },
                        {
                            "breakpoint": 900,
                            "settings": {
                                "arrows": true,
                                "dots": false,
                                "slidesToShow": 3,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        },
                        {
                            "breakpoint": 700,
                            "settings": {
                                "arrows": false,
                                "dots": false,
                                "slidesToShow": 2,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        }
                    ]
                }
            }
        },
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