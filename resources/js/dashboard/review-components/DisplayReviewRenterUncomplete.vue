<template>
    <div>
        <div v-if="hasReviews">
            <div v-for="review in reviews.data" :key="review.id"
                :class="{ 'border-b border-gray-200': notLastReview(review.renterReview.id) }">
                <div class="mt-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <div>
                            <img :src="review.renter.image" alt="profile" 
                                class="rounded-full h-14 w-14">
                        </div>
                        <div class="ml-3">
                            <h5 class="font-boldnosans font font-semibold">{{review.renter.name}}</h5>
                        </div>
                    </div>
                    <button class="focus:outline-none">
                        <i class="text-purple-400 cursor-pointer"
                        :class="{ 
                            'fas fa-times text-3xl pr-2': isThisReviewSetToEdit(review.renterReview.id),
                            'fas fa-user-edit text-2xl': !isThisReviewSetToEdit(review.renterReview.id)
                            }"
                        @click="setAsReviewEdit(review.renterReview.id)">
                        </i>
                    </button>
                </div>
                <div class="mt-2 text-gray-600">
                    <div class="text-sm">
                        <span>Rented your</span>
                        <span class="font-bold text-purple-500">
                            {{review.vehicle.year}} {{review.vehicle.make}} {{review.vehicle.model}}
                        </span>
                    </div>
                    <div class="text-xs font-semibold my-2">
                        <span>{{readableDate(review.booking.from)}}</span>
                        <span class="mx-1">&#8722;</span>
                        <span>{{readableDate(review.booking.to)}}</span>
                    </div>
                    <div class="my-2" v-if="isThisReviewSetToEdit(review.renterReview.id)">
                        <div class="mb-2">
                            <validation-errors :errors="errorFor('id')"></validation-errors>
                            <validation-errors :errors="errorFor('rating')"></validation-errors>
                            <validation-errors :errors="errorFor('content')"></validation-errors>
                        </div>
                        <div class="flex justify-start items-center">
                            <h6 class="font-bold mr-1">Rating:</h6>
                            <selectable-star-rating :size="'text-sm'"
                                                    @ratingUpdate="updateStarRating">
                            </selectable-star-rating>
                        </div>
                        <div class="lg:w-2/3 mt-2">
                            <textarea rows="5" class="border w-full px-2 py-1 focus:outline-none"
                                    v-model="content"></textarea>
                            <div>
                                <button class="text-white font-semibold py-2 text-center bg-purple-400 
                                            w-full sm:w-1/2"
                                        @click="submitReview()">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="font-semibold font-boldnosans">
                No Reviews
            </div>
        </div>
    </div>
</template>

<script>
    import SelectableStarRating from './SelectableStarRating.vue';
    import { humanReadableDate } from './../../shared/utils/dateFormats';
    import validationErrors from './../../shared/mixins/validationErrors';

    export default {
        props: {
            reviews: Object
        },

        mixins: [validationErrors],

        components: {
            SelectableStarRating
        },

        computed: {
            hasReviews() {
                return this.reviews.data.length !== 0;
            }
        },

        data() {
            return {
                reviewToEdit: null,
                rating: null,
                content: null
            }
        },
        
        methods: {
            readableDate(date) {
                return humanReadableDate(date);
            },

            // When iterating through the reviews in the template we want to add a bottom border
            // on all except the last, here we check against the host review id.
            notLastReview(id) {
                let last = this.reviews.data[this.reviews.data.length - 1];

                return id !== last.renterReview.id;
            },

            setAsReviewEdit(id) {
                if (this.reviewToEdit === id) {
                    this.resetFields();
                    this.validationErrors = null;
                } else {
                    this.reviewToEdit = id;
                    this.content = null;
                    this.validationErrors = null;
                }
            },

            isThisReviewSetToEdit(id) {
                return id === this.reviewToEdit;
            },

            updateStarRating(payload) {
                this.rating = payload;
            },

            async submitReview() {
                console.log('Hit');

                // const data = {
                //     id: this.reviewToEdit,
                //     rating: this.rating,
                //     content: this.content
                // }

                // try {
                //     let response = await axios.post('/api/dashboard/create-review-of-host', data);

                //     this.$store.dispatch('addNotification', {
                //         type: 'success',
                //         message: response.data
                //     });

                //     this.$emit('reviewSubmit');
                // } catch(error) {
                //     if (error.response.status === 422) {
                //         this.validationErrors = error.response.data.errors
                //     }
                // }
            },

            resetFields() {
                this.reviewToEdit = null;
                this.rating = 5;
                this.content = null;
            }
        },
    }
</script>