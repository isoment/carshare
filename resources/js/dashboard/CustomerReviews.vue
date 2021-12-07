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
                    <div class="mt-6 sm:mt-12">
                        <!-- Select small screen -->
                        <div class="mb-2 text-right w-full sm:hidden relative">
                            <button class="focus:outline-none px-2 py-1 border border-gray-800 text-gray-800"
                                    @click="toggleFilterMenu">
                                <span class="text-sm font-semibold">Filter</span>
                                <span><i class="fas fa-sliders-h text-sm"></i></span>
                            </button>

                            <div class="absolute bg-white right-0 top-10 rounded-sm z-40
                                        border border-gray-200 filter-dropdown-boxshadow"
                                 v-if="filterMenu"
                                 v-click-outside="filterMenuClose">
                                <div class="px-3 py-5">
                                    <h5 class="text-left text-xs font-semibold mb-1">Review type:</h5>
                                    <select class="w-full bg-white border border-gray-300 
                                                   rounded-sm text-sm focus:outline-none py-1"
                                            @change="reviewTypeSelectMobile($event)">
                                        <optgroup label="As Renter">
                                            <option value="HostIncomplete">Needs Review</option>
                                            <option value="HostComplete">You Reviewed</option>
                                        </optgroup>
                                        <optgroup label="As Host" v-if="userIsHost">
                                            <option value="RenterIncomplete">Needs Review</option>
                                            <option value="RenterComplete">You Reviewed</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-5">
                            <!-- Select large screen -->
                            <div class="border-l border-gray-300 pl-3 hidden sm:block"
                                 :class="{ 
                                    'review-select-menu': userIsHost,
                                    'review-select-menu-short': !userIsHost}"
                            >
                                <!-- Users reviews of hosts -->
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
                                        You Reviewed
                                    </div>
                                </div>
                                <!-- Users reviews of renters -->
                                <div v-if="userIsHost">
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
                                        You Reviewed
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews -->
                            <div class="col-span-5 sm:col-span-4">
                                <div>
                                    <div v-if="hostIncomplete">
                                        <of-host-incomplete></of-host-incomplete>
                                    </div>
                                    <div v-if="hostComplete">
                                        <of-host-complete></of-host-complete>
                                    </div>
                                    <div v-if="renterIncomplete">
                                        <of-renter-incomplete></of-renter-incomplete>
                                    </div>
                                    <div v-if="renterComplete">
                                        <of-renter-complete></of-renter-complete>
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
    import OfHostIncomplete from './review-components/OfHostIncomplete.vue';
    import OfHostComplete from './review-components/OfHostComplete.vue';
    import OfRenterIncomplete from './review-components/OfRenterIncomplete.vue';
    import OfRenterComplete from './review-components/OfRenterComplete.vue';

    export default {
        components: {
            OfHostIncomplete,
            OfHostComplete,
            OfRenterIncomplete,
            OfRenterComplete
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),

            userIsHost() {
                return this.user.host === 1;
            },

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
                reviewSelect: 'HostIncomplete',
                filterMenu: false
            }
        },

        methods: {
            reviewTypeSelect(value) {
                this.reviewSelect = value;
            },

            reviewTypeSelectMobile(event) {
                this.reviewSelect = event.target.value;
            },

            toggleFilterMenu() {
                this.filterMenu = !this.filterMenu;
            },

            filterMenuClose() {
                this.filterMenu = false;
            }
        }
    }
</script>

<style scoped>
    .review-select-menu {
        max-height: 11.85rem;
    }

    .review-select-menu-short {
        max-height: 5.55rem;
    }

    .filter-dropdown-boxshadow {
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
</style>