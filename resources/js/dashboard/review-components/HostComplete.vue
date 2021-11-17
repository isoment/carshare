<template>
    <div>
        <h5 class="font-semibold font-boldnosans">Completed reviews.</h5>
        <h6 class="text-gray-600 text-xs font-light">
            These are the reviews for previous bookings that you have already left.
        </h6>
        <div v-for="review in reviews" :key="review.id">

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                reviews: ''
            }
        },

        methods: {
            async fetchReviews() {
                let response = await axios.get('/api/dashboard/host-users-reviews-complete');

                this.reviews = response.data;
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>