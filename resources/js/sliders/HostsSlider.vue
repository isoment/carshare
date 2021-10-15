<template>
    <div>
        <vue-slick-carousel v-bind="carouselSettings"
                            class="mx-6"
                            v-if="topHostsExists">

            <!-- Previous Arrow -->
            <template #prevArrow="arrowOption">
                <i class="fas fa-chevron-left text-lg custom-arrow">
                    {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
                </i>
            </template>

            <!-- Next Arrow -->
            <template #nextArrow="arrowOption">
                <i class="fas fa-chevron-left text-lg custom-arrow">
                    {{ arrowOption.currentSlide }}/{{ arrowOption.slideCount }}
                </i>
            </template>

            <!-- Top Host Card -->
            <div v-for="host in topHosts" :key="host.id">
                <div class="rounded-lg shadow-md mx-2 my-5 hover:shadow-lg transition-all transform border 
                            border-gray-100 duration-300 p-3 top-host-card-height">
                    <div class="flex">
                        <div>
                            <img :src="host.host_avatar" alt="profile" 
                                class="rounded-full h-20 w-20">
                        </div>
                        <div class="flex flex-col ml-4">
                            <div class="font-bold -mb-1">{{ host.host_name }}</div>
                            <div>
                                <i class="fas fa-award text-xs text-purple-500"></i>
                                <span class="text-xs">Top Host</span>
                                <div class="text-xs">
                                    <span>{{ host.host_review_count }} trips</span>
                                    <span>•</span>
                                    <span>{{ dateFormat(host.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <star-rating :rating="host.rating" :size="'text-sm'"></star-rating>
                    </div>
                    <div class="mt-2">{{ truncateString(host.content, 85) }}</div>
                    <div class="italic font-bold text-sm mt-3">
                        {{ host.renter_name }}
                    </div>
                </div>
            </div>

        </vue-slick-carousel>
    </div>
</template>

<script>
    import VueSlickCarousel from 'vue-slick-carousel';
    import 'vue-slick-carousel/dist/vue-slick-carousel.css';
    import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css';
    import { dateFormatMonthDayYear } from './../shared/utils/dateFormats';

    export default {
        components: {
            VueSlickCarousel
        },

        data() {
            return {
                carouselSettings: {
                    "dots": false,
                    "infinite": true,
                    "initialSlide": 2,
                    "slidesToShow": 3,
                    "slidesToScroll": 1,
                    "swipeToSlide": true,
                    "speed": 200,
                    "responsive": [
                        {
                            "breakpoint": 1000,
                            "settings": {
                                "arrows": true,
                                "dots": false,
                                "slidesToShow": 2,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        },
                        {
                            "breakpoint": 650,
                            "settings": {
                                "arrows": false,
                                "dots": false,
                                "slidesToShow": 1,
                                "slidesToScroll": 1,
                                "infinite": true,
                            }
                        }
                    ]
                },

                topHosts: null,
                loading: false,
                error: null
            }
        },

        computed: {
            topHostsExists() {
                return this.topHosts !== null;
            },
        },

        async created() {
            this.loading = true;
            this.error = null;

            try {
                this.topHosts = (await axios.get('/api/top-hosts/list')).data.data;
            } catch(error) {
                this.error = error.response.status;
            }

            this.loading = false;
        },

        methods: {
            dateFormat(date) {
                return dateFormatMonthDayYear(date);
            },

            truncateString(string, length) {
                if (string.length <= length) {
                    return string;
                }

                return string.slice(0, length) + '...';
            },
        },
    }
</script>

<style scoped>
    .slick-next::before {
        color: #9f7aea;
    }

    .slick-prev::before{
        color: #9f7aea;
    }

    .top-host-card-height {
        height: 285px;
    }
</style>