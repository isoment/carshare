<template>
    <div>

    <nav class="bg-gray-800">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button type="button" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white 
                                   hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" 
                            aria-controls="mobile-menu" aria-expanded="false"
                            @click="toggleMobileMenu">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger Icon -->
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" 
                             fill="none" viewBox="0 0 24 24" 
                             stroke="currentColor" aria-hidden="true"
                             v-show="!mobileMenu">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close Icon -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" 
                             viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"
                             v-show="mobileMenu">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0 flex items-center">
                        <router-link :to="{ name: 'main-page' }" exact-active-class="e9828ad291">
                            <i class="fas fa-car text-3xl text-purple-400"></i>
                        </router-link>
                    </div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-bold">
                                Share my car
                            </a>

                            <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-bold">
                                Find a car
                            </a>

                            <router-link class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-bold"
                                         :to="{ name: 'login' }"
                                         v-if="!isLoggedIn">
                                Log in
                            </router-link>

                            <router-link class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-bold"
                                         :to="{ name: 'register' }"
                                         v-if="!isLoggedIn">
                                Sign up
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
                     v-if="isLoggedIn">
                    <button class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-purple-400">
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" 
                                    class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-purple-400" 
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                                    @click="toggleProfileMenu"
                                    v-on-clickaway="profileMenuAway">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            </button>
                        </div>

                        <transition name="fade">
                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white 
                                        ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                                role="menu" aria-orientation="vertical" 
                                aria-labelledby="user-menu-button" tabindex="-1"
                                v-show="profileMenu">

                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-purple-500" 
                                            role="menuitem" tabindex="-1" id="user-menu-item-0">
                                    Your Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-purple-500" 
                                            role="menuitem" tabindex="-1" id="user-menu-item-1">
                                    Settings
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-purple-500" 
                                   role="menuitem" tabindex="-1" id="user-menu-item-2"
                                   @click="logout">
                                    Sign out
                                </a>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <transition name="slide-fade">
            <div class="sm:hidden absolute w-full h-full bg-gray-800 z-50" id="mobile-menu"
                v-show="mobileMenu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                <!-- <a href="#" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Active</a> -->

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                    Share my car
                </a>

                <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                    Find a car
                </a>

                <router-link class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium"
                             :to="{ name: 'login' }"
                             v-if="!isLoggedIn">
                    Log in
                </router-link>

                <router-link class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium"
                             :to="{ name: 'register' }"
                             v-if="!isLoggedIn">
                    Sign up
                </router-link>
                </div>
            </div>
        </transition>
    </nav>

    </div>
</template>

<script>
    import { mixin as clickaway } from 'vue-clickaway';
    import { mapState } from 'vuex';

    export default {
        mixins: [ clickaway ],

        data() {
            return {
                profileMenu: false,
                mobileMenu: false,
            }
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn"
            }),
        },

        methods: {
            toggleMobileMenu() {
                this.mobileMenu = !this.mobileMenu;
            },

            toggleProfileMenu() {
                this.profileMenu = !this.profileMenu;
            },

            profileMenuAway() {
                this.profileMenu = false;
            },

            async logout() {
                try {
                    axios.post('/logout');

                    this.$store.dispatch('logOut');

                    this.$store.dispatch('addNotification', {
                        type: 'success',
                        message: 'Logout successful'
                    });
                } catch(error) {
                    this.$store.dispatch('logOut');
                }
            }
        }
    }
</script>