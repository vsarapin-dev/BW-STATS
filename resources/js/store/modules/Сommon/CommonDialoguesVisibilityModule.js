export default {
    state: () => ({
        // Dialog visibility
        isOpenedCreateNewDialog: false,
        isOpenedFilterDialog: false,
        isOpenedDeleteAlertDialog: false,
        isOpenedFilesShareDialog: false,

        // Extra data
        deleteApproved: false,
        shouldUpdateTableRow: false,
        dialogHeaderName: null,
    }),
    getters: {
        isOpenedCreateNewDialog: state => state.isOpenedCreateNewDialog,
        isOpenedFilterDialog: state => state.isOpenedFilterDialog,
        isOpenedDeleteAlertDialog: state => state.isOpenedDeleteAlertDialog,
        isOpenedFilesShareDialog: state => state.isOpenedFilesShareDialog,
        dialogHeaderName: state => state.dialogHeaderName,
        shouldUpdateTableRow: state => state.shouldUpdateTableRow,
        deleteApproved: state => state.deleteApproved,
    },
    mutations: {
        SET_FILES_SHARE_DIALOG_VISIBILITY(state, payload) {
            state.isOpenedFilesShareDialog = payload;
        },
        SET_DELETE_APPROVED(state, payload) {
            state.deleteApproved = payload;
        },
        SET_DELETE_ALERT_DIALOG_VISIBILITY(state, payload) {
            state.isOpenedDeleteAlertDialog = payload;
        },
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
