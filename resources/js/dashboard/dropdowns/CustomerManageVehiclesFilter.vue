<template>
    <div class="inline">
        <!-- Toggle Button -->
        <a>
            <i class="fas fa-cogs ml-1 cursor-pointer"
               @click="toggleMenu">
            </i>
        </a>

        <!-- Filter Dropdown -->
        <transition name="fade">
            <div class="absolute py-4 px-2 bg-white border border-gray-200 rounded-md shadow-lg 
                        left-0 top-16 text-sm font-normal manage-vehicles-filtermenu z-20"
                 v-if="menuState"
                 v-click-outside="closeMenu">

                <div>
                    <div class="relative">
                        <div class="manage-vehicles-filter-heading">
                            <h4 class="font-bold text-lg mb-2 font-boldnosans tracking-widest text-purple-500">Filter Options:</h4>
                        </div>
                        <div class="absolute bg-purple-100 manage-vehicles-filter-headingbar"></div>
                    </div>
                    
                    <div class="flex flex-row items-center justify-between">
                        <label for="active" class="text-sm font-semibold">Only vehicles in service:</label>
                        <input type="checkbox" name="active" 
                               class="text-purple-300 h-3 w-3"
                               v-model="inputs.outOfService">
                    </div>
                </div>

            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                menuState: false,
                inputs: {
                    outOfService: false
                }
            }
        },

        methods: {
            toggleMenu() {
                this.menuState = !this.menuState;
            },

            closeMenu() {
                this.menuState = false;
            }
        }
    }
</script>

<style>
    .manage-vehicles-filtermenu {
        width: 16rem;
    }

    .manage-vehicles-filter-heading {
        z-index: 90;
    }

    .manage-vehicles-filter-headingbar {
        height: 0.6rem;
        width: 5.2rem;
        z-index: -1;
        bottom: 0.1rem;
        left: 0.65rem;
    }
</style>