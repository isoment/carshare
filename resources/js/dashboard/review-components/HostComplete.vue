<template>
    <div>
        <h5 class="font-semibold font-boldnosans">Completed reviews.</h5>
        <h6 class="text-gray-600 text-xs font-light">
            These are the reviews for previous bookings that you have already left.
        </h6>
        <!-- Add an if statement to ensure the component is only loaded once reviews are -->
        <div class="mt-2" v-if="reviews">
            <review-paginator :reviews="reviews"
                              @pageChanged="pageChanged">
                              
            </review-paginator>
        </div>
    </div>
</template>

<script>
    import ReviewPaginator from './ReviewPaginator.vue';

    export default {
        components: {ReviewPaginator},

        data() {
            return {
                reviews: null,
                page: 1
            }
        },

        methods: {
            async fetchReviews() {
                let response = await axios.get(`/api/dashboard/host-users-reviews-complete?page=${this.page}`);

                this.reviews = response.data;
            },

            pageChanged(payload) {
                this.page = payload;
                this.fetchReviews();
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>