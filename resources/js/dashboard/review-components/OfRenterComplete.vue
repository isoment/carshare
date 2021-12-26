<template>
    <div>
        <review-summary></review-summary>
        <div class="border rounded-md">
            <div class="mx-4 my-2">
                <div class="pb-2 mb-4 border-b border-gray-200">
                    <h5 class="font-semibold font-boldnosans">Completed reviews.</h5>
                    <h6 class="text-gray-600 text-xs font-light">
                        These are the reviews you have left for users who booked your vehicles.
                    </h6>
                </div>
                <!-- Paginator of reviews index -->
                <div class="mt-2" v-if="reviews">
                    <simple-paginator :iterable="reviews"
                                    @pageChanged="pageChanged">
                        <display-review-renter-complete :reviews="reviews"></display-review-renter-complete>
                    </simple-paginator>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SimplePaginator from './../../shared/components/SimplePaginator.vue';
    import DisplayReviewRenterComplete from './DisplayReviewRenterComplete.vue';
    import ReviewSummary from './ReviewSummary.vue';

    export default {
        components: {
            SimplePaginator,
            DisplayReviewRenterComplete,
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
                let response = await axios.get(`/api/dashboard/renter-users-reviews-complete?page=${this.page}`);

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