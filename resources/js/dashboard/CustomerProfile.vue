<template>
    <div>
        <main-navigation></main-navigation>

        <div v-if="!isLoggedIn">
            <unauthorized></unauthorized>
        </div>

        <div v-else>
            <!-- Banner -->
            <div class="customer-profile-banner h-36 md:h-56 border-b border-gray-200 pb-8">
                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="relative">
                        <div class="absolute right-2 lg:right-8 top-16 md:top-28">
                            <a href="#" 
                               class="bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                      px-4 py-2 text-white font-bold">
                                Edit profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="relative mr-12">
                        <div class="absolute -top-14 mb-12">
                            <img src="/storage/avatar-seeder-img/05.jpg" 
                                 alt="avatar"
                                 class="rounded-full h-28 w-28 border-8 border-white">
                        </div>
                        <div class="mt-20">
                            <h3 class="text-3xl font-extrabold">Customer Name</h3>
                            <h5 class="text-sm mt-1">Joined Jul 2021</h5>
                        </div>
                        <div class="mt-10">
                            <h6 class="uppercase font-bold font-boldnosans text-xs 
                                    text-gray-500 tracking-widest mb-4">
                                Verified Info
                            </h6>
                            <div>
                                <div class="flex justify-between items-center">
                                    <div class="text-sm">
                                        Approved to drive
                                    </div>
                                    <a href="#" class="text-purple-500 font-bold text-sm">
                                        Verify License
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-200">
                        TEST
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import { mapState } from "vuex";

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn"
            }),
        }
    }
</script>