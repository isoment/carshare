<template>
    <div class="mx-4 my-2">
        <div class="pb-2 mb-4 border-b border-gray-200">
            <h5 class="font-semibold font-boldnosans">Leave reviews here.</h5>
            <h6 class="text-gray-600 text-xs font-light">
                Tell us if the vehicle you rented met expectations, how the experience with the host was
                and any other relevant information you care to.
            </h6>
        </div>

        <div class="mt-2">
            TEST
        </div>
    </div>
</template>

<script>
    import ReviewPaginator from './ReviewPaginator.vue';

    export default {
        data() {
            return {
                reviews: null
            }
        },

        methods: {
            async fetchReviews() {
                let response = await axios.get('/api/dashboard/host-users-reviews-uncomplete');

                this.reviews = response.data;
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>