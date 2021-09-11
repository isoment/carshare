import { isLoggedIn, logOut } from "./shared/utils/auth";

export default {
    state: {
        isLoggedIn: false,
        user: {},
        notifications: [],
        cart: {
            items: []
        },
        searchDates: {
            start: null,
            end: null
        },
        priceRange: {
            min: null,
            max: null
        }
    },

    mutations: {
        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        },

        setUser(state, payload) {
            state.user = payload;
        },

        /*
            Payload is formatted... We also want to add an id
            {
                type: 'error',
                message: .....
                id: .....
            }
        */
        pushNotification(state, payload) {
            state.notifications.push({
                ...payload,
                id: (Math.random().toString(36) + Date.now().toString(36)).substr(2)
            })
        },

        // Return all the notifications except the one specified in the payload.
        removeNotification(state, payload) {
            state.notifications = state.notifications.filter(notification => {
                return notification.id !== payload.id;
            })
        },

        addToCart(state, payload) {
            state.cart.items.push({
                ...payload,
                id: (Math.random().toString(36) + Date.now().toString(36)).substr(2)
            });
        },

        removeFromCart(state, payload) {
            state.cart.items = state.cart.items.filter(item => item.id !== payload);
        },

        setCart(state, payload) {
            state.cart = payload;
        },

        setSearchDates(state, payload) {
            state.searchDates = payload;
        },

        setPriceRange(state, payload) {
            state.priceRange = payload;
        },
    },

    actions: {
        // Load stored state when we initialize Vue.
        loadStoredState(context) {
            // Get the last date seach from local storage.
            const lastSearchDates = localStorage.getItem('searchDates');

            const cart = localStorage.getItem('cart');

            if (lastSearchDates) {
                context.commit('setSearchDates', JSON.parse(lastSearchDates));
            }

            if (cart) {
                context.commit('setCart', JSON.parse(cart));
            }

            // Set the logged in user.
            context.commit('setLoggedIn', isLoggedIn());
        },

        // Load user information.
        async loadUser(context) {
            if (isLoggedIn()) {
                try {
                    const user = (await axios.get('/api/user-details')).data.data;
                    console.log(user);
                    context.commit('setUser', user);
                    context.commit('setLoggedIn', true);
                } catch (error) {
                    context.dispatch("logOut");
                }
            }
        },

        // Logout a user.
        logOut(context) {
            context.commit("setUser", {});
            context.commit("setLoggedIn", false);
            context.dispatch("clearCart");
            logOut();
        },

        addNotification(context, payload) {
            context.commit('pushNotification', payload);
        },

        removeNotification(context, payload) {
            context.commit('removeNotification', payload);
        },

        addToCart(context, payload) {
            context.commit('addToCart', payload);
            localStorage.setItem('cart', JSON.stringify(context.state.cart));
        },

        removeFromBasket(context, payload) {
            context.commit('removeFromCart', payload);
            localStorage.setItem('cart', JSON.stringify(context.state.cart));
        },

        clearCart(context) {
            context.commit('setCart', { items: [] });
            localStorage.setItem('cart', JSON.stringify(context.state.cart));
        },

        // Set the search dates and store in local storage.
        setSearchDates(context, payload) {
            context.commit('setSearchDates', payload);
            localStorage.setItem('searchDates', JSON.stringify(payload));
        },

        // Set the price range and store in local storage.
        setPriceRange(context, payload) {
            context.commit('setPriceRange', payload);
        },
    },
}