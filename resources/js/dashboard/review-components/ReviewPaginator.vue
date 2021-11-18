<template>
    <div>
        <!-- Review Index -->
        <div>
            <!-- <div v-for="review in reviews.data" :key="review.id">
                <div class="mt-2">
                    <div>{{review.host.name}}</div>
                    <div>{{review.hostReview.id}}</div>
                </div>
            </div> -->
        </div>

        <!-- Pagination controls -->
        <div class="mt-3" v-if="multiplePages">
            <div class="flex items-center justify-between">
                <button class="bg-gray-300 rounded-sm px-3 py-1 text-white text-sm font-bold 
                                tracking-widest"
                        :class="{ 'bg-purple-400 hover:bg-purple-300 transition-all duration-200': !onFirstPage }"
                        :disabled="onFirstPage"
                        @click="prevPage">
                    Prev
                </button>
                <button class="bg-gray-300 rounded-sm px-3 py-1 text-white text-sm font-bold 
                                tracking-widest"
                        :class="{ 'bg-purple-400 hover:bg-purple-300 transition-all duration-200' : !onLastPage }"
                        :disabled="onLastPage"
                        @click="nextPage">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            reviews: Object
        },

        data() {
            return {
                page: null,
                lastPage: null,
            }
        },

        computed: {
            multiplePages() {
                return this.lastPage > 1;
            },

            onLastPage() {
                return this.page === this.lastPage
            },

            onFirstPage() {
                return this.page === 1;
            }
        },

        methods: {
            prevPage() {
                if (this.page > 1) {
                    this.page--
                    this.$emit('pageChanged', this.page);
                }
            },

            nextPage() {
                if (this.page < this.lastPage) {
                    this.page++
                    this.$emit('pageChanged', this.page);
                }
            },

            // Set the page info state from the reviews prop
            setPaginationData() {
                this.page = this.reviews.meta.current_page ?? 1;
                this.lastPage = this.reviews.meta.last_page ?? null;
            }
        },

        created() {
            this.setPaginationData();
        }
    }
</script>