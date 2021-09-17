<template>
    <div>
        <main-navigation></main-navigation>

        <div v-if="!isLoggedIn || !pid">
            <unauthorized></unauthorized>
        </div>

        <div v-else>

        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        computed: {
            ...mapState({
                user: "user",
                isLoggedIn: "isLoggedIn",
            }),

            pid() {
                return this.$route.params.pid;
            }
        }
    }
</script>