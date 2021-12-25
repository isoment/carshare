<template>
    <div class="mb-4" v-if="ratings !== null">
        <div>
            <h5 class="mb-1 font-bold font-boldnosans">Your ratings</h5>
        </div>
        <div class="flex flex-row main-summary-wrapper">
            <div class="p-4 border rounded-md w-1/3 text-center flex flex-col items-center">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Total</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-4xl text-gray-800 font-bold font-boldnosans header-rating">
                            {{ratings.total}}
                        </h5>
                    </div>
                    <div class="h-12 w-12 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-star text-purple-400 text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-4 border rounded-md w-1/3 ml-4 text-center flex flex-col items-center">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Renter</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-4xl text-gray-800 font-bold font-boldnosans header-rating">
                            {{ratings.asRenter}}
                        </h5>
                    </div>
                    <div class="h-12 w-12 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-star text-purple-400 text-3xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-4 border rounded-md w-1/3 ml-4 text-center flex flex-col items-center">
                <div class="relative">
                    <div class="header-bar-wrap">
                        <h6 class="font-bold font-boldnosans mb-2">Host</h6>
                    </div>
                    <div class="absolute bg-purple-200 header-bar"></div>
                </div>
                <div class="flex items-center">
                    <div class="mr-2">
                        <h5 class="text-4xl text-gray-800 font-bold font-boldnosans header-rating">
                            {{userIsHost ? ratings.asHost : 'N/A'}}
                        </h5>
                    </div>
                    <div class="h-12 w-12 rounded-full border-2 border-purple-300 flex 
                                justify-center items-center shadow-lg star-rating-icon">
                        <i class="fas fa-star text-purple-400 text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        computed: {
            ...mapState({
                user: "user"
            }),

            userIsHost() {
                return this.user.host === 1;
            }
        },

        data() {
            return {
                ratings: null
            }
        },

        methods: {
            async fetchRatings() {
                let response = await axios.get('/api/dashboard/show-review-rating');

                this.ratings = response.data;
            }
        },

        created() {
            this.fetchRatings();
        }
    }
</script>

<style scoped>
    .header-wrap-wrap {
        z-index: 20;
    }

    .header-bar {
        height: 0.65rem;
        width: 2.5rem;
        z-index: -1;
        top: 0.8rem;
    }

    @media screen and (max-width: 470px) {
        .header-rating {
            font-size: 1.5rem;
        }

        .star-rating-icon {
            height: 1.6rem;
            width: 1.6rem;
        }

        .star-rating-icon > i {
            font-size: 1rem;
        }
    }

    @media screen and (max-width: 350px) {
        .header-rating {
            font-size: 3rem;
        }

        .star-rating-icon {
            height: 3rem;
            width: 3rem;
        }

        .star-rating-icon > i {
            font-size: 1.875rem;
        }

        .main-summary-wrapper {
            flex-direction: column;
        }

        .main-summary-wrapper > div {
            width: 100%;
            margin-bottom: 5px;
            margin-left: 0px;
        }
    }
</style>