<template>
    <div>
        <main-navigation></main-navigation>
        <div>
            <div v-if="!isLoggedIn">
                <error :message="'Not Authorized'"></error>
            </div>

            <div v-else>
                <div class="customer-profile-banner h-36 border-b border-gray-200 pb-8">
                </div>

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="relative md:mr-20">
                        <div class="absolute -top-8 mb-12">
                            <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                                <h3 class="text-lg font-boldnosans font-bold">Reviews</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="mt-12">
                        <div class="grid grid-cols-5">
                            <!-- Select -->
                            <div class="border-l border-gray-300 pl-3 review-select-menu">
                                <!-- From Hosts -->
                                <div class="mb-5">
                                    <h4 class="text-lg font-boldnosans font-semibold tracking-wider mb-2">
                                        As Renter
                                    </h4>
                                    <div class="my-1 ml-2 text-purple-400 font-bold text-sm cursor-pointer 
                                               hover:text-purple-500"
                                         :class="{ 'text-purple-800 hover:text-purple-800': hostIncomplete }"
                                         @click="reviewTypeSelect('HostIncomplete')">
                                        Needs Review
                                    </div>
                                    <div class="mt-2 ml-2 text-purple-400 font-bold text-sm cursor-pointer
                                               hover:text-purple-500"
                                         :class="{ 'text-purple-800 hover:text-purple-800': hostComplete }"
                                         @click="reviewTypeSelect('HostComplete')">
                                        Has Review
                                    </div>
                                </div>
                                <!-- From Renters -->
                                <div>
                                    <h4 class="text-lg font-boldnosans font-semibold tracking-wider mb-2">
                                        As Host
                                    </h4>
                                    <div class="my-1 ml-2 text-purple-400 font-bold text-sm cursor-pointer
                                               hover:text-purple-500 transition-all duration-200"
                                         :class="{ 'text-purple-800 hover:text-purple-800': renterIncomplete }"
                                         @click="reviewTypeSelect('RenterIncomplete')">
                                        Needs Review
                                    </div>
                                    <div class="mt-2 ml-2 text-purple-400 font-bold text-sm cursor-pointer
                                               hover:text-purple-500 transition-all duration-200"
                                         :class="{ 'text-purple-800 hover:text-purple-800': renterComplete }"
                                         @click="reviewTypeSelect('RenterComplete')">
                                        Has Review
                                    </div>
                                </div>
                            </div>
                            <!-- Reviews -->
                            <div class="border rounded-md col-span-4 p-2">
                                <div>
                                    <div v-if="hostIncomplete">
                                        <host-incomplete></host-incomplete>
                                    </div>
                                    <div v-if="hostComplete">
                                        <host-complete></host-complete>
                                    </div>
                                    <div v-if="renterIncomplete">
                                        <renter-incomplete></renter-incomplete>
                                    </div>
                                    <div v-if="renterComplete">
                                        <renter-complete></renter-complete>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import HostIncomplete from './review-components/HostIncomplete.vue';
    import HostComplete from './review-components/HostComplete.vue';
    import RenterIncomplete from './review-components/RenterIncomplete.vue';
    import RenterComplete from './review-components/RenterComplete.vue';

    export default {
        components: {
            HostIncomplete,
            HostComplete,
            RenterIncomplete,
            RenterComplete
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            hostIncomplete() {
                return this.reviewSelect === 'HostIncomplete';
            },

            hostComplete() {
                return this.reviewSelect === 'HostComplete';
            },

            renterIncomplete() {
                return this.reviewSelect === 'RenterIncomplete';
            },

            renterComplete() {
                return this.reviewSelect === 'RenterComplete';
            }
        },

        data() {
            return {
                reviewSelect: 'HostComplete'
            }
        },

        methods: {
            reviewTypeSelect(value) {
                this.reviewSelect = value;
            }
        }
    }
</script>

<style scoped>
    .review-select-menu {
        max-height: 11.85rem;
    }
</style>