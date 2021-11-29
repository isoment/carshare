<template>
    <div>
        <review-summary></review-summary>
        <div class="border rounded-md">
            <div class="mx-4 my-2">
                <div class="pb-2 mb-4 border-b border-gray-200">
                    <h5 class="font-semibold font-boldnosans">Completed reviews.</h5>
                    <h6 class="text-gray-600 text-xs font-light">
                        These are the reviews for previous bookings that you have already left.
                    </h6>
                </div>
                <!-- 
                    Add an if statement to ensure the review paginator component is only loaded 
                    when the reviews are. We pass DisplayReviewHostComplete component into the
                    paginator using a slot.
                -->
                <div class="mt-2" v-if="reviews">
                    <review-paginator :reviews="reviews"
                                    @pageChanged="pageChanged">
                        <display-review-host-complete :reviews="reviews"></display-review-host-complete>        
                    </review-paginator>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ReviewPaginator from './ReviewPaginator.vue';
    import DisplayReviewHostComplete from './DisplayReviewHostComplete.vue';
    import ReviewSummary from './ReviewSummary.vue';

    export default {
        components: {
            ReviewPaginator, 
            DisplayReviewHostComplete,
            ReviewSummary
        },

        data() {
            return {
                reviews: null,
                page: 1
            }
        },

        methods: {
            async fetchReviews() {
                let response = await axios.get(`/api/dashboard/host-users-reviews-complete?page=${this.page}`);

                console.log(response);

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