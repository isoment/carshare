<template>
    <div>
        <review-summary></review-summary>
        <div class="border rounded-md">
            <div class="mx-4 my-2">
                <div class="pb-2 mb-4 border-b border-gray-200">
                    <h5 class="font-semibold font-boldnosans">Leave reviews here.</h5>
                    <h6 class="text-gray-600 text-xs font-light">
                        Tell us if the vehicle you rented met expectations, how the experience with the host was
                        and any other relevant information you care to.
                    </h6>
                </div>

                <div class="mt-2" v-if="reviews">
                    <simple-paginator :iterable="reviews"
                                    @pageChanged="pageChanged">
                        <display-review-host-uncomplete :reviews="reviews"
                                                        @reviewSubmit="reviewSubmitted">
                        </display-review-host-uncomplete>
                    </simple-paginator>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SimplePaginator from './../../shared/components/SimplePaginator.vue';
    import DisplayReviewHostUncomplete from './DisplayReviewHostUncomplete.vue';
    import ReviewSummary from './ReviewSummary.vue';

    export default {
        components: {
            SimplePaginator,
            DisplayReviewHostUncomplete,
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
                let response = await axios.get(`/api/dashboard/host-users-reviews-uncompleted?page=${this.page}`);

                this.reviews = response.data;
            },

            pageChanged(payload) {
                this.page = payload;
                this.fetchReviews();
            },

            // Reload all the reviews once a new one is submitted
            reviewSubmitted() {
                this.reviews = null;
                this.page = 1;
                this.fetchReviews();
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>