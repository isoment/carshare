import { isLoggedIn, logOut } from "./shared/utils/auth";

export default {
    state: {
        isLoggedIn: false,
        user: {},
        notifications: [],
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

        // Return all the notifications except the one specified in the payload
        removeNotification(state, payload) {
            state.notifications = state.notifications.filter(notification => {
                return notification.id !== payload.id;
            })
        }
    },

    actions: {
        // Load stored state when we initialize Vue
        loadStoredState(context) {
            context.commit('setLoggedIn', isLoggedIn());
        },

        // Load user information
        async loadUser(context) {
            if (isLoggedIn()) {
                try {
                    const user = (await axios.get('/user')).data;
                    context.commit('setUser', user);
                    context.commit('setLoggedIn', true);
                } catch (error) {
                    context.dispatch("logOut");
                }
            }
        },

        // Logout a user
        logOut(context) {
            context.commit("setUser", {});
            context.commit("setLoggedIn", false);
            logOut();
        },

        // Add a notification
        addNotification(context, payload) {
            context.commit('pushNotification', payload)
        },

        // Remove a notification
        removeNotification(context, payload) {
            context.commit('removeNotification', payload);
        }
    },
}