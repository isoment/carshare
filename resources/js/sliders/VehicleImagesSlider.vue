<template>
    <div>
        <vue-slick-carousel class="vehicle-image-slider" v-bind="carouselSettings">
            <div class="vehicle-image-wrap" v-for="image in vehicleImages" :key="image.id">
                <img :src="imageString(image)" 
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

        props: {
            vehicleImages: Array
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
        },

        methods: {
            imageString(image) {
                return '/' + image;
            }
        }
    }
</script>

<style scoped>

</style>