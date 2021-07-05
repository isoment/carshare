<template>
    <div>

        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <div class="text-center mt-8"
             v-if="loading">
            <i class="fas fa-spinner fa-spin text-purple-500 text-4xl"></i>
        </div>

        <div v-else>
            <!-- Vehicle Images -->
            <vehicle-images-slider :vehicle-images="vehicleData.vehicle_images"></vehicle-images-slider>

            <div class="my-10 max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="grid grid-cols-3">

                    <!-- Left Pane -->
                    <div class="col-span-2 mr-4">

                        <div class="grid grid-cols-4">
                            <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                                The car
                            </div>
                            <div class="col-span-3 flex flex-col mx-2">
                                <h2 class="font-bold text-4xl font-boldnosans">
                                    {{vehicleData.vehicle_make}} {{vehicleData.vehicle_model}} {{vehicleData.year}}
                                </h2>
                                <div class="my-2">
                                    <span class="font-boldnosans font-bold text-xl">{{vehicleData.vehicle_rating}}</span>
                                    <span><i class="fas fa-star text-purple-500 text-md"></i></span>
                                    <span class="font-light text-sm">({{vehicleData.vehicle_trip_count}} trips)</span>
                                </div>
                                <div class="grid grid-cols-2 text-lg mt-4">
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <img src="/img/safety-seat.svg" alt="" class="h-7 w-7">
                                        </div>
                                        <div>
                                            {{vehicleData.seats}} Seats
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <img src="/img/car-door.svg" alt="" class="h-7 w-7">
                                        </div>
                                        <div class="ml-1">
                                            {{vehicleData.doors}} Doors
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 mt-12">
                            <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                                Hosted by
                            </div>
                            <div class="col-span-3 mx-2">
                                <div class="flex items-center">
                                    <div class="relative">
                                        <img src="/img/avatar-female.jpeg" alt="avatar" 
                                            class="h-20 w-20 rounded-full">
                                        <div class="absolute bg-white shadow-lg rounded-full border border-gray-50 px-4
                                                    flex top-16">
                                            <span class="mr-1 font-semibold">
                                                {{vehicleData.host_rating}}
                                                </span>
                                            <span><i class="fas fa-star text-purple-500 text-md"></i></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-boldnosans font-bold text-lg">
                                            {{vehicleData.host_name}}
                                        </h3>
                                        <div class="text-sm">
                                            <span>{{numberFormatComma(vehicleData.host_total_trips)}} trips</span> • 
                                            <span>Joined {{dateMonthYear(vehicleData.member_since)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>                        
                        </div>

                        <div class="grid grid-cols-4 mt-12">
                            <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                                Description
                            </div>
                            <div class="col-span-3 mx-2">
                                <p>
                                    {{vehicleData.description}}
                                </p>
                            </div>                        
                        </div>

                        <div class="grid grid-cols-4 mt-12">
                            <div class="mx-3 text-right mt-2 font-boldnosans font-bold tracking-widest text-sm">
                                Ratings and reviews
                            </div>
                            <div class="col-span-3 mx-2">
                                <!-- Total rating and review count -->
                                <div>
                                    <div class="flex items-center">
                                        <span class="font-boldnosans font-bold text-3xl mr-1">4.87</span>
                                        <span><i class="fas fa-star text-purple-500 text-lg"></i></span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        (21 ratings)
                                    </div>
                                </div>

                                <!-- Vehicle Reviews -->
                                <div class="mt-6">
                                    <h5 class="font-bold uppercase font-boldnosans text-gray-500 text-sm">Reviews</h5>
                                    <review-list></review-list>
                                </div>
                            </div>                        
                        </div>

                    </div>

                    <!-- Right Pane -->
                    <div class="font-boldnosans">
                        <div class="mb-1">
                            <span class="font-bold text-xl font-boldnosans">${{vehicleData.price}}</span>
                            <span>/</span>
                            <span>day</span>
                        </div>
                        <div class="text-xs text-gray-600 underline mb-3">
                            $547 est total
                        </div>
                        <hr>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import VehicleImagesSlider from './../sliders/VehicleImagesSlider';
    import ReviewList from './../review/ReviewList';
    import moment from 'moment';

    export default {   
        components: {
            VehicleImagesSlider,
            ReviewList
        },

        data() {
            return {
                loading: false,
                vehicleData: null
            }
        },

        methods: {
            dateMonthYear(date) {
                return moment(date).format('MMMM YYYY');
            },

            numberFormatComma(number) {
                return new Intl.NumberFormat().format(number)
            },

            async fetchVehicle() {
                this.loading = true;

                this.vehicleData = (await axios.get(`/api/vehicle-show/${this.$route.params.id}`)).data.data

                this.loading = false;
            }
        },

        created() {
            this.fetchVehicle();
        }
    }
</script>