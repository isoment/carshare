<template>
    <div>
        <!-- Review Index -->
        <div>
            <slot></slot>
        </div>

        <!-- Pagination controls -->
        <div class="mt-3" v-if="multiplePages">
            <div class="flex items-center justify-between">
                <button class="bg-gray-300 rounded-sm px-3 py-1 text-white text-sm font-bold 
                                tracking-widest focus:outline-none"
                        :class="{ 'bg-purple-400 hover:bg-purple-300 transition-all duration-200': !onFirstPage }"
                        :disabled="onFirstPage"
                        @click="prevPage">
                    Prev
                </button>
                <button class="bg-gray-300 rounded-sm px-3 py-1 text-white text-sm font-bold 
                                tracking-widest focus:outline-none"
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
    /**
     *  A simple paginator for general use. The iterable is passed in as a prop. Every time the Prev or Next
     *  button is clicked an even is emitted to the parent that with the current page. We can also pass in
     *  a styled card or list item in the slot for displaying each iterable. 
     */
    export default {
        props: {
            iterable: Object
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

            // Set the page info state from the iterable prop
            setPaginationData() {
                this.page = this.iterable.meta.current_page ?? 1;
                this.lastPage = this.iterable.meta.last_page ?? null;
            },
        },

        created() {
            this.setPaginationData();
        }
    }
</script>