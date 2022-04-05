<template>
    <div class="ml-3 relative" v-if="user.profile">
        <div>
            <button type="button" 
                    class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-purple-400" 
                    id="user-menu-button"
                    @click="toggleProfileMenu">
                <span class="sr-only">Open user menu</span>
                <img class="h-8 w-8 rounded-full"
                    :src="avatar(user.profile.image)"
                    alt="avatar">
            </button>
        </div>

        <transition name="fade">
            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md py-1 bg-white profile-dropdown-boxshadow
                        ring-1 ring-black ring-opacity-5 focus:outline-none profile-dropdown" 
                    v-if="profileMenu"
                    v-click-outside="profileMenuAway">

                <router-link class="block px-4 py-2 text-sm font-bold text-gray-700 hover:text-purple-500" 
                                role="menuitem" tabindex="-1" id="user-menu-item-0"
                                :to="{ name: 'customer-profile' }">
                    My Profile
                </router-link>

                <router-link class="block px-4 py-2 text-sm font-bold text-gray-700 hover:text-purple-500" 
                                role="menuitem" tabindex="-1" id="user-menu-item-0"
                                :to="{ name: 'customer-vehicles' }">
                    My Cars
                </router-link>

                <router-link class="block px-4 py-2 text-sm font-bold text-gray-700 hover:text-purple-500" 
                                role="menuitem" tabindex="-1" id="user-menu-item-0"
                                :to="{ name: 'customer-bookings' }">
                    Bookings
                </router-link>

                <router-link class="block px-4 py-2 text-sm font-bold text-gray-700 hover:text-purple-500" 
                                role="menuitem" tabindex="-1" id="user-menu-item-0"
                                :to="{ name: 'customer-reviews' }">
                    Reviews
                </router-link>

                <router-link class="block px-4 py-2 text-sm font-bold text-gray-700 hover:text-purple-500" 
                                role="menuitem" tabindex="-1" id="user-menu-item-0"
                                :to="{ name: 'customer-statistics' }">
                    Statistics
                </router-link>

                <a href="#" class="block px-4 py-2 text-sm font-bold text-gray-700 hover:text-purple-500" 
                role="menuitem" tabindex="-1" id="user-menu-item-2"
                @click="logout">
                    Sign out
                </a>
            </div>
        </transition>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import avatarHelper from './../mixins/avatarHelper';

    export default {
        mixins: [ avatarHelper ],

        data() {
            return {
                profileMenu: false,
            }
        },

        computed: {
            ...mapState({
                isLoggedIn: "isLoggedIn",
                user: "user"
            }),
        },

        methods: {
            toggleProfileMenu() {
                this.profileMenu = !this.profileMenu;
            },

            profileMenuAway() {
                this.profileMenu = false;
            },

            async logout() {
                try {
                    await axios.post('/logout');

                    this.$store.dispatch('logOut');

                    this.$router.push({ name: 'main-page' }).catch(error => {
                        if (
                            error.name !== 'NavigationDuplicated' &&
                            !error.message.includes('Avoided redundant navigation to current location')
                        ) {
                            console.error(error);
                        }
                    });

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

<style>
    .profile-dropdown-boxshadow {
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
</style>