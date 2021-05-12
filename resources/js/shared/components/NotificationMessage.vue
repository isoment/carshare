<template>
    <div :class="notificationColor" class="text-white shadow-md pl-4 pr-3 py-3 rounded-md text-sm my-2" 
         role="alert">
        <div class="flex justify-between items-center">
            <span>{{ notification.message }}</span>
            <span class="px-1">
                <i :class="notificationIcon"></i>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['notification'],

        computed: {
            notificationColor() {
                if (this.notification.type === 'error') {
                    return 'bg-red-400';
                }
                return 'bg-green-400';
            },

            notificationIcon() {
                if (this.notification.type === 'error') {
                    return 'fas fa-exclamation-circle text-lg';
                }
                return 'fas fa-check-circle text-lg';
            }
        },

        // Remove the message after 3 seconds
        created() {
            setTimeout(() => {
                this.$store.dispatch("removeNotification", this.notification);
            }, 3000);
        },
    }
</script>

<style>

</style>