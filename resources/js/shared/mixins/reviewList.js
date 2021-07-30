export default {
    data() {
        return {
            reviews: [],
            page: 1,
            lastPage: 1,
        }
    },

    computed: {
        stillMoreResults() {
            if (this.page !== this.lastPage) {
                return true;
            }
        }
    },

    methods: {
        loadMoreReviews() {
            if (this.page >= this.lastPage) { 
                return;
            };

            this.page++;

            this.fetchReviews();
        },
    }
}