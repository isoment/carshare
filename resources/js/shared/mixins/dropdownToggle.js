export default {
    data() {
        return {
            menuState: false
        }
    },

    methods: {
        toggleFilterMenu() {
            var _this = this;

            // Remove or add an event listener based on where the click was
            const closeListener = (e) => {
                if (_this.catchOutsideClick(e, _this.$refs.menubutton, _this.$refs.filtermenu)) {
                    window.removeEventListener('click', closeListener)
                    _this.menuState = false;
                }
            }

            window.addEventListener('click', closeListener);

            this.menuState = !this.menuState;
        },

        // Determine if a click is outside the menu or the button
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