export default {
    state: () => ({
        snackBarOpened: false,
        message: '',
    }),
    getters: {
        snackBarOpened: state => state.snackBarOpened,
        message: state => state.message,
    },
    mutations: {
        SET_SNACKBAR_OPENED(state, payload) {
            state.snackBarOpened = payload;
        },
        SET_MESSAGE(state, payload) {
            state.message = payload;
        },
    }
}
