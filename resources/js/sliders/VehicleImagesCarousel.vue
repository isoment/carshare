<template>
    <div class="relative" v-if="vehicleImages">
        <div class="carousel-img">
            <img :src="currentImage" alt="project-image">
        </div>
        <div class="absolute bottom-2 left-6">
            <div class="bg-black text-white rounded-full text-xs border-2 border-white px-2 py-1 opacity-70
                        font-semibold">
                <span>{{imageCounter}}</span>
            </div>
        </div>
        <div class="prev-arrow">
            <button class="text-2xl text-white bg-black rounded-full 
                           w-12 h-12 opacity-70 focus:outline-none"
                    @click="prevImage()">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="next-arrow">
            <button class="text-2xl text-white bg-black rounded-full 
                           w-12 h-12 opacity-70 focus:outline-none"
                    @click="nextImage()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            vehicleImages: Array
        },

        data() {
            return {
                activeImage: 0
            }
        },

        computed: {
            currentImage() {
                return this.vehicleImages[this.activeImage];
            },

            imageCounter() {
                return `${this.activeImage + 1} of ${this.vehicleImages.length}`
            }
        },

        methods: {
            nextImage() {
                let nextImage = this.activeImage + 1;
                // If we are on the last image set the next image to the beginning
                if (nextImage >= this.vehicleImages.length) {
                    nextImage = 0;
                }
                this.activeImage = nextImage;
            },

            prevImage() {
                let prevImage = this.activeImage - 1;
                // If we are on the first image set the previous image to the end
                if (prevImage < 0) {
                    prevImage = this.vehicleImages.length - 1;
                }
                this.activeImage = prevImage;
            }
        }
    }
</script>

<style>
    .carousel-img {
        height: 250px;
    }

    .carousel-img img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    @media screen and (min-width:680px) {
        .carousel-img {
            height: 450px;
        }
    }

    @media screen and (min-width:1100px) {
        .carousel-img {
            height: 600px;
        }
    }

    @media screen and (min-width:1400px) {
        .carousel-img {
            height: 700px;
        }
    }

    .prev-arrow {
        position: absolute;
        top: 45%;
        left: 1rem;
    }

    .prev-arrow button i {
        margin-left: -4px;
    }

    .next-arrow {
        position: absolute;
        top: 45%;
        right: 1rem;
    }

    .next-arrow button i {
        margin-right: -4px;
    }
</style>