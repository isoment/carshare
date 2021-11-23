<template>
    <div class="flex">
        <span v-for="(rating, index) in rating" :key="rating.id">
            <i class="fas fa-star text-purple-500"
               :class="starSize"
               @click="starClicked(index)">
            </i>
        </span>
        <span v-for="(empty, index) in emptyStars" :key="empty.id">
            <i class="far fa-star text-purple-500"
               :class="starSize"
               @click="emptyStarClicked(index)">
            </i>
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            size: String
        },

        computed: {
            starSize() {
                return this.size ? this.size : 'text-lg'
            },

            emptyStars() {
                return 5 - this.rating;
            }
        },

        data() {
            return {
                rating: 5
            }
        },

        methods: {
            starClicked(index) {
                this.rating = index + 1;
                this.$emit('ratingUpdate', this.rating);
            },

            emptyStarClicked(index) {
                this.rating = this.rating + (index + 1);
                this.$emit('ratingUpdate', this.rating);
            }
        },

        created() {
            this.$emit('ratingUpdate', this.rating);
        }
    }
</script>