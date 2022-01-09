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

        props: {
            userId: Number
        },

        mixins: [reviewList],

        methods: {
            determineId() {
                return this.userId ?? this.$store.state.user.id;
            },

            async fetchReviews() {
                const idToUse = this.determineId();

                const reviews = (await axios.get(`/api/reviews-from-renters/${idToUse}?page=${this.page}`));

                this.reviews.push(...reviews.data.data);

                this.lastPage = reviews.data.meta.last_page;
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>