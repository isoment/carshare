<template>
    <div>
        <div class="flex border-b border-gray-200 mt-5 pb-5"
             v-for="review in reviews" :key="review.id">
            <div class="flex-shrink-0 mr-4">
                <img :src="avatar(review.renter_avatar)" alt="avatar" 
                    class="h-12 w-12 rounded-full">
            </div>
            <div>
                <div class="flex">
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                    <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                </div>
                <div class="text-xs my-1">
                    <span>{{review.renter_name}}</span>
                    <span class="text-gray-500">{{dateFormat(review.updated_at)}}</span>
                </div>
                <div>
                    <p>{{review.content}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import avatarHelper from './../shared/mixins/avatarHelper';

    export default {
        mixins: [avatarHelper],

        data() {
            return {
                reviews: null
            }
        },

        methods: {
            dateFormat(date) {
                return moment(date).format('MMMM D YYYY');
            },

            async fetchReviews() {
                this.reviews = (await axios.get(`/api/reviews-vehicle/${this.$route.params.id}`)).data.data;
            }
        },

        created() {
            this.fetchReviews();
        }
    }
</script>