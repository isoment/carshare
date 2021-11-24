<template>
    <div class="mx-4 my-2">
        <div class="pb-2 mb-4 border-b border-gray-200">
            <h5 class="font-semibold font-boldnosans">Reviews of renters.</h5>
            <h6 class="text-gray-600 text-xs font-light">
                These are the reviews you have left for users who booked your vehicles.
            </h6>
        </div>
        <!-- Paginator of reviews index -->
        <div class="mt-2" v-if="reviews">
            <review-paginator :reviews="reviews"
                              @pageChanged="pageChanged">
                <display-review-renter-complete :reviews="reviews"></display-review-renter-complete>
            </review-paginator>
        </div>
    </div>
</template>

<script>
    import ReviewPaginator from './ReviewPaginator.vue';
    import DisplayReviewRenterComplete from './DisplayReviewRenterComplete.vue';

    export default {
        components: {
            ReviewPaginator,
            DisplayReviewRenterComplete
        },

        data() {
            return {
                reviews: null,
                page: 1
            }
        },

        methods: {
            async fetchReviews() {
                let response = await axios.get(`/api/dashboard/renter-users-reviews-complete`);

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