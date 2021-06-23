<template>
    <div>
        <vue-slick-carousel class="vehicle-image-slider" v-bind="carouselSettings">
            <div class="vehicle-image-wrap">
                <img src="/storage/vehicle-seeder-img/honda-3219ur1hfo9eq.jpg" 
                     alt="" class="vehicle-image-img">
            </div>
            <div class="vehicle-image-wrap">
                <img src="/storage/vehicle-seeder-img/mercedes-32732798.webp" 
                     alt="" class="vehicle-image-img">
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
                    "dots": true,
                    "arrows": false,
                    "dotsClass": "slick-dots custom-dot-class",
                    "edgeFriction": 0.35,
                    "infinite": true,
                    "speed": 500,
                    "slidesToShow": 1,
                    "slidesToScroll": 1
                }
            }
        }
    }
</script>

<style scoped>

</style>