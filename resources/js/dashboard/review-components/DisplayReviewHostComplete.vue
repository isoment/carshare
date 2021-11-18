<template>
    <div>
        <div v-for="review in reviews.data" :key="review.id"
             :class="{ 'border-b border-gray-200': notLastReview(review.hostReview.id) }">
            <div class="mt-3">
                <div class="flex items-center">
                    <div>
                        <img :src="review.host.image" alt="profile" 
                            class="rounded-full h-14 w-14">
                    </div>
                    <div class="ml-3">
                        <h5 class="font-boldnosans font font-semibold">{{review.host.name}}</h5>
                        <div>
                            <star-rating 
                                :rating="review.hostReview.rating"
                                :size="'text-sm'">
                            </star-rating>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 text-gray-600">
                <div class="text-xs font-semibold">
                    <span>{{readableDate(review.booking.from)}}</span>
                    <span class="mx-1">&#8722;</span>
                    <span>{{readableDate(review.booking.to)}}</span>
                </div>
                <div class="text-sm">
                    <span>You rented the</span>
                    <span class="font-bold text-purple-500">
                        {{review.vehicle.year}} {{review.vehicle.make}} {{review.vehicle.model}}
                    </span>
                </div>
                <div class="my-2 text-sm">
                    {{review.hostReview.content}}
                </div>
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

        methods: {
            readableDate(date) {
                return humanReadableDate(date);
            },

            // When iterating through the reviews in the template we want to add a bottom border
            // on all except the last, here we check against the host review id.
            notLastReview(id) {
                let last = this.reviews.data[this.reviews.data.length - 1];

                return id !== last.hostReview.id;
            }
        },
    }
</script>