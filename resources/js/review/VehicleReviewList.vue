<template>
    <div>
        <div class="flex border-b border-gray-200 mt-5 pb-5"
             v-for="review in reviews" :key="review.id">
            <div class="flex-shrink-0 mr-4">
                <img :src="avatar(review.reviewer_avatar)" alt="avatar" 
                    class="h-12 w-12 rounded-full">
            </div>
            <div>
                <div class="flex">
                    <span v-for="rating in review.rating" :key="rating.id">
                        <i class="fas fa-star text-purple-500 text-lg"></i>
                    </span>
                </div>
                <div class="text-xs my-1">
                    <span>{{review.reviewer_name}}</span>
                    <span class="text-gray-500">{{dateFormat(review.updated_at)}}</span>
                </div>
                <div>
                    <p>{{review.content}}</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6" v-if="stillMoreResults">
            <button class="bg-purple-500 text-white hover:bg-purple-400 px-4 py-2 font-bold
                            transition-all duration-200"
                    @click="loadMoreReviews">See more</button>
        </div>

        <div class="text-center mt-6 font-bold text-gray-500 text-sm" v-else>
            No more reviews
        </div>
    </div>
</template>

<script>
    import { dateFormatMonthDayYear } from './../shared/utils/dateFormats';
    import avatarHelper from './../shared/mixins/avatarHelper';

    export default {
        mixins: [avatarHelper],

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
            dateFormat(date) {
                return dateFormatMonthDayYear(date);
            },

            async fetchReviews() {
                let reviews = (await axios.get(`/api/reviews-vehicle/${this.$route.params.id}?page=${this.page}`));

                this.reviews.push(...reviews.data.data);

                this.lastPage = reviews.data.meta.last_page;
            },

            loadMoreReviews() {
                if (this.page >= this.lastPage) { 
                    return;
                };

                this.page++;

                this.fetchReviews();
            },
        },

        created() {
            this.fetchReviews();
        }
    }
</script>