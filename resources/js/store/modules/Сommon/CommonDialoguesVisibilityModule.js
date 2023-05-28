export default {
    state: () => ({
        // Dialog visibility
        isOpenedCreateNewDialog: false,
        isOpenedFilterDialog: false,

        // Extra data
        shouldUpdateTableRow: false,
        dialogHeaderName: null,
    }),
    getters: {
        isOpenedCreateNewDialog: state => state.isOpenedCreateNewDialog,
        isOpenedFilterDialog: state => state.isOpenedFilterDialog,
        dialogHeaderName: state => state.dialogHeaderName,
        shouldUpdateTableRow: state => state.shouldUpdateTableRow,
    },
    mutations: {
        SET_SHOULD_UPDATE_TABLE_ROW(state, payload) {
            state.shouldUpdateTableRow = payload;
        },
        SET_DIALOG_HEADER_NAME(state, payload) {
            state.dialogHeaderName = payload;
        },
        SET_CREATE_NEW_DIALOG_VISIBILITY(state, payload) {
            state.isOpenedCreateNewDialog = payload;
        },
        SET_FILTER_DIALOG_VISIBILITY(state, payload) {
            state.isOpenedFilterDialog = payload;
        }
    }
}
