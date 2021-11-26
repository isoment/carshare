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
            <div class="absolute py-4 px-2 bg-white border border-gray-200 rounded-md left-0 top-16 text-sm 
                        font-normal manage-vehicles-filtermenu z-20 manage-vehicles-filter-boxshadow"
                 v-if="menuState"
                 v-click-outside="closeMenu">

                <div>
                    <div class="relative mb-4">
                        <div class="manage-vehicles-filter-heading">
                            <h4 class="font-bold text-lg mb-2 font-boldnosans tracking-widest text-purple-500">
                                Filter Options:
                            </h4>
                        </div>
                        <div class="absolute bg-purple-100 manage-vehicles-filter-headingbar"></div>
                    </div>
                    
                    <div class="flex flex-row items-center justify-between">
                        <label for="active" class="text-sm font-semibold">Vehicle is active:</label>
                        <input type="checkbox" name="active"
                               class="text-purple-300 h-4 w-4"
                               v-model="inputs.active"
                               @change="emitInputs()">
                    </div>
                    <div class="flex flex-row items-center justify-between mt-2">
                        <label for="priceSort" class="text-sm font-semibold">Sort by price:</label>
                        <select name="priceSort" id="priceSort" 
                                class="bg-white border border-gray-200 rounded-sm focus:outline-none"
                                v-model="inputs.priceSort"
                                @change="emitInputs()">
                            <option value="desc">Highest</option>
                            <option value="asc">Lowest</option>
                        </select>
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
                    active: true,
                    priceSort: "desc"
                }
            }
        },

        methods: {
            toggleMenu() {
                this.menuState = !this.menuState;
            },

            closeMenu() {
                this.menuState = false;
            },

            emitInputs() {
                this.$emit('inputUpdated', this.inputs);
            }
        },

        created() {
            this.emitInputs();
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

    .manage-vehicles-filter-boxshadow {
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
</style>