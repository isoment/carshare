<template>
    <div>
        <main-navigation></main-navigation>
        <div>
            <div v-if="!isLoggedIn">
                <error :message="'Not Authorized'"></error>
            </div>

            <div v-else>
                <div class="customer-profile-banner h-36 border-b border-gray-200 pb-8">
                    <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6"></div>
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
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),
        }
    }
</script>