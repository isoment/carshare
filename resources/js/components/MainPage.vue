<template>
    <div>
        <!-- Main Navigation -->
        <main-navigation></main-navigation>

        <!-- Hero Section -->
        <section class="pb-24 md:pb-60 pt-24 hero-background">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-7xl font-bold text-white font-boldnosans">Don't rent... Share.</h1>
                    <h3 class="text-xl text-white font-bold tracking-wider mt-2">Book awesome, unique cars in your area.</h3>
                </div>
                <div class="flex justify-center mt-3">
                    <div class="bg-white rounded-lg md:rounded-full py-2 px-2 md:pl-5 md:pr-16 md:pt-2 md:pb-1 
                                flex flex-col md:flex-row w-11/12 sm:w-3/4 md:w-auto shadow-lg">
                        <!-- Make search -->
                        <div class="flex flex-col md:pr-2 border-b md:border-b-0 md:border-r border-gray-300 my-2 md:my-0">
                            <label for="make" class="text-xs font-bold text-gray-500">Vehicle Make</label>
                            <input type="text" name="make" class="focus:outline-none font-semibold text-sm search-input-width">
                        </div>
                        <div class="relative">

                            <date-picker v-model="range" 
                                         color="purple" 
                                         is-range
                                         :min-date="new Date()">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <div class="flex flex-col md:flex-row justify-around">
                                        <div class="flex flex-col md:border-r border-gray-300 md:px-3 border-b 
                                                    md:border-b-0 my-2 md:my-0">
                                            <label for="from" class="text-xs font-bold text-gray-500">From</label>
                                            <input class="focus:outline-none search-input-width"
                                                    :value="inputValue.start"
                                                    v-on="inputEvents.start">
                                        </div>
                                        <div class="flex flex-col md:px-3 border-b md:border-b-0 border-gray-300 my-2 md:my-0">
                                            <label for="until" class="text-xs font-bold text-gray-500">Until</label>
                                            <input class="focus:outline-none search-input-width"
                                                    :value="inputValue.end"
                                                    v-on="inputEvents.end">
                                        </div>
                                    </div>
                                </template>
                            </date-picker>

                            <div class="absolute magnifier-button hidden md:block">
                                <button class="text-white bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                               rounded-full focus:outline-none"
                                        @click="search">
                                    <i class="fas fa-search text-2xl w-12 h-12 pt-2"></i>
                                </button>
                            </div>

                            <div class="md:hidden">
                                <button class="text-white font-bold bg-purple-500 hover:bg-purple-400 transition-all 
                                               duration-200 focus:outline-none py-2 px-4 w-full"
                                        @click="search">
                                    <div class="flex items-center justify-center">
                                        <div class="mr-2">Search for cars</div>
                                        <i class="fas fa-search"></i>
                                    </div>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Info Section -->
        <section>
            <div class="relative flex justify-center">
                <div class="absolute bg-white rounded-2xl px-10 py-3 insurance-logo">
                    <img src="/img/logo_libertymutual.png">
                </div>
            </div>
            <div class="text-center mt-28 mb-8 mx-2 sm:mx-6 md:mx-10 flex flex-col justify-center items-center">
                <h4 class="font-bold text-xl mb-6">Drive with confidence</h4>
                <h2 class="font-boldnosans text-3xl sm:text-4xl font-bold max-w-2xl">Exciting fun cars for any budget without the worry or hassle</h2>
            </div>
            <div class="max-w-6xl mx-auto px-2 sm:px-6 lg:px-8 mt-14">
                <div class="flex flex-col mx-2 sm:mx-6 md:mx-14 lg:flex-row lg:mx-0 justify-center">
                    <div class="mb-12 lg:mb-0 flex flex-col items-center sm:items-start">
                        <img src="/img/info-icon-1.svg" class="h-10 w-10 mb-3">
                        <h3 class="font-black text-bold font-boldnosans mb-2">Wide selection of cars</h3>
                        <p class="text-gray-700 font-light mb-2 text-center sm:text-left">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores illo quia, eaque velit ullam sit magni ad. Aspernatur odit excepturi.</p>
                        <a href="#" class="font-bold text-purple-600 hover:text-purple-500">Learn More</a>
                    </div>
                    <div class="mb-12 lg:mb-0 flex flex-col items-center sm:items-start">
                        <img src="/img/info-icon-2.svg" class="h-10 w-10 mb-3 -ml-2">
                        <h3 class="font-black text-bold font-boldnosans mb-2">Cars near you</h3>
                        <p class="text-gray-700 font-light mb-2 text-center sm:text-left">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores illo quia, eaque velit ullam sit magni ad. Aspernatur odit excepturi.</p>
                        <a href="#" class="font-bold text-purple-600 hover:text-purple-500 transition-all duration-200">
                            Learn More
                        </a>
                    </div>
                    <div class="mb-12 lg:mb-0 flex flex-col items-center sm:items-start">
                        <img src="/img/info-icon-3.svg" class="h-10 w-10 mb-3">
                        <h3 class="font-black text-bold font-boldnosans mb-2">Strict safety standards</h3>
                        <p class="text-gray-700 font-light mb-2 text-center sm:text-left">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores illo quia, eaque velit ullam sit magni ad. Aspernatur odit excepturi.</p>
                        <a href="#" class="font-bold text-purple-600 hover:text-purple-500 transition-all duration-200">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Car Make Slider -->
        <section class="md:mb-24 mt-6 md:mt-32">
            <div class="slider-width mx-auto px-2 sm:px-6 lg:px-8 md:mt-14">
                <h3 class="font-black text-bold font-boldnosans mb-4 mx-8 text-xl">Browse by make</h3>
                <vehicle-make-slider></vehicle-make-slider>
            </div>
        </section>

        <!-- Travelogue -->
        <section class="my-12 md:my-24">
            <div class="text-center mt-12 md:mt-28 mb-8 flex flex-col justify-center items-center overflow-hidden">
                <div class="mx-2 sm:mx-6 md:mx-10">
                    <div class="relative">
                        <div>
                            <h2 class="font-boldnosans text-3xl sm:text-5xl font-bold max-w-2xl z-40">Find your adventure</h2>
                        </div>
                        <div class="absolute bg-purple-100 travelogue-purple-bar"></div>
                    </div>
                    <div class="mt-8">
                        <h5 class="text-gray-700 font-light">Stoke your wanderlust with some dreamy photo chronicles of road trip adventures.</h5>
                    </div>
                    <div class="mt-10">
                        <a class="bg-purple-500 hover:bg-purple-400 text-white py-4 px-6 font-lg font-bold 
                                transition-all duration-200" 
                        href="#">
                            Explore Adventure
                        </a>
                    </div>
                </div>
                <div class="max-w-6xl px-2 sm:px-6 lg:px-8 mt-14">
                    <div class="pl-32 overflow-hidden relative">
                        <div class="travelogue-photo-div"></div>
                        <div class="bg-gray-900 text-white px-6 py-8 text-left travelogue-detail-box absolute">
                            <h5 class="uppercase text-xs font-semibold">FEATURED TRAVELOGUE</h5>
                            <h5 class="font-bold text-2xl mt-4">An Olympic experience in Washington</h5>
                            <p class="font-light mt-4">Discover the epic waterfalls, moody weather, and lush forests of coastal Washington.</p>
                            <div class="mt-4">
                                <a href="#" class="underline font-semibold">Read more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top Hosts Slider -->
        <section class="md:mb-24 mt-6 md:mt-32">
            <div class="slider-width mx-auto px-2 sm:px-6 lg:px-8 md:mt-14">
                <div class="flex flex-col justify-center items-center">
                    <div class="text-center relative">
                        <div>
                            <h2 class="font-boldnosans text-3xl sm:text-5xl font-bold max-w-2xl z-40 mb-4 md:mb-12">Meet the hosts</h2>
                        </div>
                        <div class="absolute bg-purple-100 meet-hosts-purple-bar"></div>
                    </div>
                </div>
                <h3 class="font-black text-bold font-boldnosans mb-2 mx-8 text-xl">Our top hosts</h3>
                <hosts-slider></hosts-slider>
            </div>
        </section>

    </div>
</template>

<script>
    import VehicleMakeSlider from './../sliders/VehicleMakeSlider';
    import HostsSlider from './../sliders/HostsSlider';
    import Calendar from 'v-calendar/lib/components/calendar.umd';
    import DatePicker from 'v-calendar/lib/components/date-picker.umd';
    import { dateTypeCheck } from './../shared/utils/dateHelpers';
    import moment from 'moment';

    export default {
        components: {
            VehicleMakeSlider,
            HostsSlider,
            Calendar,
            DatePicker
        },

        data() {
            return {
                range: {
                    start: null,
                    end: null
                },
            };
        },

        methods: {
            search() {
                // Call the action to set local storage
                this.$store.dispatch('setSearchDates', {
                    start: dateTypeCheck(this.range.start),
                    end: dateTypeCheck(this.range.end)
                });

                console.log('Dates Set!');
            },
        },

        created() {
            // If search dates have been stored in the local storage through vuex we
            // use those, else set the start date to today and end to tomorrow
            let today = new Date();

            this.range.start = this.$store.state.searchDates.start ? 
                this.$store.state.searchDates.start :
                new Date().toLocaleDateString();

            this.range.end = this.$store.state.searchDates.end ?
                this.$store.state.searchDates.end :
                new Date(today.getTime() + (24 * 60 * 60 * 1000)).toLocaleDateString();
        }
    }
</script>