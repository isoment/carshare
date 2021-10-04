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
            <div class="absolute py-4 px-2 bg-white border border-grey-50 rounded-md shadow-md 
                        left-0 top-16 filtermenu text-sm font-normal"
                 v-if="menuState"
                 v-click-outside="closeMenu">
                <div class="flex flex-col">
                    <label for="active">Active</label>
                    <input type="checkbox" name="active" v-model="inputs.outOfService" class="border">
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

<style scoped>
    .filtermenu {
        width: 16rem;
    }
</style>