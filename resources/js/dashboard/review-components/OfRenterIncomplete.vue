<template>
    <div class="mx-4 my-2">
        <div class="pb-2 mb-4 border-b border-gray-200">
            <h5 class="font-semibold font-boldnosans">Leave reviews for those who've rented your vehicles.</h5>
            <h6 class="text-gray-600 text-xs font-light">
                Here you can review renters of your vehicles.
            </h6>
        </div>

        <div class="mt-2" v-if="reviews">
            <review-paginator :reviews="reviews"
                              @pageChanged="pageChanged">
                <display-review-renter-uncomplete :reviews="reviews"
                                                  @reviewSubmit="reviewSubmitted">
                </display-review-renter-uncomplete>
            </review-paginator>
        </div>
    </div>
</template>

<script>
    import ReviewPaginator from './ReviewPaginator.vue';
    import DisplayReviewRenterUncomplete from './DisplayReviewRenterUncomplete.vue';

    export default {
        components: {
            ReviewPaginator,
            DisplayReviewRenterUncomplete
        },

        data() {
            return {
                reviews: null,
                page: 1
            }
        },

        methods: {
            async fetchReviews() {
                let response = await axios.get(`/api/dashboard/renter-users-reviews-uncompleted?page=${this.page}`);

                this.reviews = response.data
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