<template>
    <div>
        <main-navigation></main-navigation>
        <div v-if="!isLoggedIn">
            <error :message="'Not Authorized'"></error>
        </div>
        <div v-else>
            <div class="customer-profile-banner h-36 border-b border-gray-200 pb-8"></div>

            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                <div class="h-20 w-32 rounded-sm relative"
                        :style="{ 'background-image': 'url(' + 'x' + ')' }"
                        style="background-size: cover; background-position: 50% 50%;">
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
                Text
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
        },

        methods: {
            async fetchBooking() {
                try {
                    let response = await axios.get(`/api/dashboard/show-booking/${this.$route.params.id}`);
                    console.log(response);
                } catch (error) {
                    console.log(error.response);
                }
            }
        },

        created() {
            this.fetchBooking();
        }
    }
</script>