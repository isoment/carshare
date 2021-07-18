<template>
    <div>
        <main-navigation></main-navigation>

        <div v-if="!isLoggedIn">
            <unauthorized></unauthorized>
        </div>

        <div v-else>
            <div class="customer-profile-banner h-56 border-b border-gray-200 pb-8">
                <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                    <div class="relative">
                        <div class="absolute right-8 top-28">
                            <a href="#" 
                               class="bg-purple-500 hover:bg-purple-400 transition-all duration-200 
                                      px-4 py-2 text-white font-bold">
                                Edit profile
                            </a>
                        </div>
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