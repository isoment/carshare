<template>
    <div>
        <individual-review :reviews="reviews"
                           :stillMoreResults="stillMoreResults"
                           @loadMoreReviews="loadMoreReviews()">
        </individual-review>
    </div>
</template>

<script>
    import reviewList from './../shared/mixins/reviewList';
    import IndividualReview from './../review/IndividualReview.vue';

    export default {
        components: { IndividualReview },

        mixins: [reviewList],

        methods: {
            async fetchReviews() {
                let reviews = (await axios.get(`/api/reviews-from-renters/${this.$store.state.user.id}?page=${this.page}`));

                this.reviews.push(...reviews.data.data);

                this.lastPage = reviews.data.meta.last_page;
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>