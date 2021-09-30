<template>
    <div class="flex">
        <span v-for="rating in rating" :key="rating.id">
            <i class="fas fa-star text-purple-500"
               :class="starSize">
            </i>
        </span>
        <span v-for="star in emptyStars" :key="star.id">
            <i class="far fa-star text-purple-500"
               :class="starSize">
            </i>
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            rating: Number,
            size: String
        },

        computed: {
            starSize() {
                return this.size ? this.size : 'text-lg'
            },

            emptyStars() {
                return 5 - this.rating;
            }
        }
    }
</script>