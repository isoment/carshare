<template>
    <div>
        <div v-if="hasReviews">
            <div v-for="review in reviews.data" :key="review.id"
                :class="{ 'border-b border-gray-200': notLastReview(review.renterReview.id) }">
                <div class="mt-3">
                    <div class="flex items-center">
                        <div>
                            <img :src="review.renter.image" alt="profile" 
                                class="rounded-full h-14 w-14">
                        </div>
                        <div class="ml-3">
                            <h5 class="font-boldnosans font font-semibold">{{review.renter.name}}</h5>
                            <div>
                                <star-rating 
                                    :rating="review.renterReview.rating"
                                    :size="'text-sm'">
                                </star-rating>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-gray-600">
                    <div class="text-sm">
                        <span>Rented your</span>
                        <span class="font-bold text-purple-500">
                            {{review.vehicle.year}} {{review.vehicle.make}} {{review.vehicle.model}}
                        </span>
                    </div>
                    <div class="text-xs font-semibold">
                        <span>{{readableDate(review.booking.from)}}</span>
                        <span class="mx-1">&#8722;</span>
                        <span>{{readableDate(review.booking.to)}}</span>
                    </div>
                    <div class="my-2 text-sm">
                        {{review.renterReview.content}}
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
    import { humanReadableDate } from './../../shared/utils/dateFormats';

    export default {
        props: {
            reviews: Object
        },

        computed: {
            hasReviews() {
                return this.reviews.data.length !== 0;
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
            }
        }
    }
</script>