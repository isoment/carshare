import { isLoggedIn, logOut } from "./shared/utils/auth";

export default {
    state: {
        isLoggedIn: false,
        user: {},
        notifications: [],
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
        setUser(state, payload) {
            state.user = payload;
        },

        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
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

        // Set the search dates.
        setSearchDates(state, payload) {
            state.searchDates = payload;
        },

        // Set the price range
        setPriceRange(state, payload) {
            state.priceRange = payload;
        },
    },

    actions: {
        // Load stored state when we initialize Vue.
        loadStoredState(context) {
            // Set the logged in user.
            context.commit('setLoggedIn', isLoggedIn());

            // Get the last date seach from local storage.
            const lastSearchDates = localStorage.getItem('searchDates');

            if (lastSearchDates) {
                context.commit('setSearchDates', JSON.parse(lastSearchDates));
            }
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
            logOut();
        },

        // Add a notification.
        addNotification(context, payload) {
            context.commit('pushNotification', payload);
        },

        // Remove a notification.
        removeNotification(context, payload) {
            context.commit('removeNotification', payload);
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