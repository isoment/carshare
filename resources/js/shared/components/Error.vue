<template>
    <div>
        <div class="text-gray-600 h-screen flex items-center justify-center margin-adjust-error-comp">
            <div class="text-center">
                <div class="text-7xl">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h1 class="text-4xl font-bold1 mt-4 mb-2">{{formatMessage}}</h1>
                <div class="text-gray-500">
                    <span>You cannot complete this request, return to</span>
                    <span>
                        <router-link :to="{ name: formatRouteName }" 
                                     class="text-purple-300 hover:text-purple-500 font-bold">
                            {{formatRouteDesc}}
                        </router-link>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            message: String,
            routeName: String,
            routeDesc: String
        },

        computed: {
            formatMessage() {
                return this.message ? this.message : 'There was an error';
            },

            formatRouteName() {
                return this.routeName ? this.routeName : 'main-page';
            },

            formatRouteDesc() {
                return this.routeDesc ? this.routeDesc : 'home page';
            }
        }
    }
</script>

<style>
    .margin-adjust-error-comp {
        margin-top: -64px;
    }
</style>