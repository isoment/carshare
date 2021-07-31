<template>
    <div>
        <div v-if="reviews.length">
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
                        @click="$emit('loadMoreReviews')">See more</button>
            </div>

            <div class="text-center mt-6 font-bold text-gray-500 text-sm" v-else>
                No more reviews
            </div>
        </div>

        <div v-else
             class="pt-3">
            <no-reviews message="User doesn't have any reviews yet"></no-reviews>
        </div>

    </div>
</template>

<script>
    import { dateFormatMonthDayYear } from './../shared/utils/dateFormats';
    import avatarHelper from './../shared/mixins/avatarHelper';
    import NoReviews from './../review/NoReviews.vue';

    export default {
        props: {
            reviews: Array,
            stillMoreResults: Boolean
        },

        mixins: [avatarHelper],

        components: {
            NoReviews
        },

        methods: {
            dateFormat(date) {
                return dateFormatMonthDayYear(date);
            },
        }
    }
</script>