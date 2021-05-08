import { isLoggedIn } from "./shared/utils/auth";

export default {
    state: {
        isLoggedIn: false,
        user: {}
    },

    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },

        setLoggedIn(state, payload) {
            state.isLoggedIn = payload;
        }
    },

    actions: {
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
                    console.log(user);
                }
            }
        }
    },
}