<template>
    <div>

        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
            
            <div>
                <div class="pt-4 pb-2 mb-2 border-b border-gray-100 flex">
                    <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2
                                text-gray-700 focus:outline-none hover:bg-gray-100 hover:border-gray-100
                                transition-all duration-300"
                            @click="toggleDatesMenu">
                        <i class="far fa-calendar-alt text-md"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Change dates
                        </div>
                    </button>
                    <button class="border rounded-md border-gray-300 flex items-center py-2 px-3 mb-2 ml-2
                                text-gray-700 focus:outline-none hover:bg-gray-100 hover:border-gray-100
                                transition-all duration-300">
                        <i class="fas fa-sliders-h"></i>
                        <div class="font-bold font-mono text-sm ml-2">
                            Filters
                        </div>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-8 shadow-lg border border-gray-50 rounded-lg"
                     v-for="vehicle in vehicles" 
                     :key="vehicle.id">
                    <div>
                        <h3 class="font-bold font-boldnosans text-xl">{{ vehicle.vehicle_make }} {{ vehicle.model }}</h3>
                        <h6 class="font-light text-sm">{{ vehicle.year }}</h6>
                        <h6 class="font-light text-sm">Vehicle ID {{ vehicle.id }}</h6>
                    </div>
                    <div class="text-right font-bold text-sm text-purple-500 mt-6">
                        ${{ vehicle.price_day }} / Day
                    </div>
                </div>
            </div>
            <div class="text-center mt-8"
                 v-if="loading">
                  <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
            </div>
            <div v-if="endOfResults" class="my-4">
                No More Vehicles
            </div>
            <div v-observe-visibility="handleScrolledToBottom"
                 v-if="vehicles.length">
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                vehicles: [],
                page: 1,
                lastPage: 1,
                endOfResults: false,
                datesMenu: false,
            }
        },

        methods: {
            toggleDatesMenu() {
                this.datesMenu = !this.datesMenu;
            },

            async fetchVehicles() {
                this.loading = true;

                let vehicles = await axios.get(`/api/vehicles-index?page=${this.page}`);

                // Each time this method is called we will push the 
                // new page to the vehicles array
                this.vehicles.push(...vehicles.data.data);

                // Each time this method is called we update the last_page from
                // the laravel paginator.
                this.lastPage = vehicles.data.meta.last_page;

                this.loading = false;
            },

            handleScrolledToBottom(isVisible) {
                if (!isVisible) { return };

                // If we are on the last page return
                if (this.page >= this.lastPage) { 
                    return;
                };

                this.page++;

                this.fetchVehicles();
            }
        },

        created() {
            // Set the dates based on query strings, we do this so manual changes
            // to the URL are reflected in the local store
            this.$store.dispatch('setSearchDates', {
                start: this.$route.query.start,
                end: this.$route.query.end
            })

            this.fetchVehicles();
        }
    }
</script>

<style scoped>

</style>