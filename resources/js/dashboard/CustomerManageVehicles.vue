<template>
    <div>
        <div class="relative md:mr-20">
            <div class="absolute -top-8 mb-12">
                <div class="rounded border-2 border-purple-400 bg-white px-6 py-3">
                    <h3 class="text-lg font-boldnosans font-bold">
                        <span>Manage Vehicles</span>
                        <button>
                            <i class="fas fa-cogs ml-1 cursor-pointer"
                               ref="menubutton"
                               @click="toggleFilterMenu"></i>
                        </button>
                    </h3>
                </div>
            </div>
            <!-- Filter dropdown -->
            <transition name="fade">
                <div class="absolute py-4 px-2 bg-white border border-grey-50 rounded-md shadow-md 
                            top-8 filtermenu"
                    ref="filtermenu"
                    v-if="menuState">
                    Test menu content
                </div>
            </transition>

        </div>

        <div class="py-8">
            Vehicle index will go here
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
                    if (_this.catchOutsideClick(e, _this.$refs.menubutton, _this.$refs.filtermenu)) {
                        window.removeEventListener('click', closeListener)
                        _this.menuState = false;
                    }
                }

                window.addEventListener('click', closeListener);

                this.menuState = !this.menuState;
            },

            // Determine if a click is outside the menu
            catchOutsideClick(event, button, menu) {
                if (button == event.target || menu == event.target) {
                    return false;
                }

                if (this.menuState && button != event.target) {
                    return true;
                }

                if (this.menuState && menu != event.target) {
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