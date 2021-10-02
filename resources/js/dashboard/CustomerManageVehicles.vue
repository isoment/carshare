<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">
                        <span>Manage Vehicles</span>
                        <i class="fas fa-cogs ml-1 cursor-pointer"
                           ref="dropmenu"
                           @click="toggleFilterMenu"></i>
                    </h3>
                </div>
            </div>
            <!-- Filter dropdown -->
            <div class="absolute py-4 px-2 bg-white border border-grey-50 rounded-md shadow-md 
                        top-8 filtermenu"
                 v-if="menuState">
                TEST
            </div>
        </div>

        <div class="py-8">
            TEST
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                menuState: false
            }
        },

        methods: {
            toggleFilterMenu() {
                var _this = this;

                // Remove or add an event listener
                const closeListener = (e) => {
                    if (_this.catchOutsideClick(e, _this.$refs.dropmenu)) {
                        window.removeEventListener('click', closeListener)
                        _this.menuState = false;
                    }
                }

                window.addEventListener('click', closeListener);

                this.menuState = !this.menuState;
            },

            // Determine if a click is outside the menu
            catchOutsideClick(event, dropdown) {
                if (dropdown == event.target) {
                    return false;
                }

                if (this.menuState && dropdown != event.target) {
                    return true;
                }
            }
        }
    }
</script>

<style scoped>
    .filtermenu {
        width: 16rem;
    }
</style>