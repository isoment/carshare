<template>
    <div class="notifications-list">
        <transition-group name="fade" tag="div">
            <notification-message v-for="notification in notifications"
                                :key="notification.id"
                                :notification="notification">
            </notification-message>
        </transition-group>
    </div>
</template>

<script>
    import NotificationMessage from "./NotificationMessage";
    import { mapState } from 'vuex';

    export default {
        components: {
            NotificationMessage,
        },

        computed: {
            ...mapState({
                notifications: "notifications"
            }),
        }
    }
</script>

<style>
    .notifications-list {
        position: fixed;
        bottom: 0;
        right: 0;
        margin-right: 10px;
        min-width: 200px;
    }
</style>