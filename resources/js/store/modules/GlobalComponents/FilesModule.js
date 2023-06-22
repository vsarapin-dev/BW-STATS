import API from "../../../api";

export default {
    state: () => ({
        files: [],
        userFiles: [],
        sharedFiles: [],
        selectedFiles: [],
        usersSelected: [],
        users: [],
    }),
    getters: {
        files: state => state.files,
        selectedFiles: state => state.selectedFiles,
        userFiles: state => state.userFiles,
        sharedFiles: state => state.sharedFiles,
        usersSelected: state => state.usersSelected,
        users: state => state.users,
    },
    mutations: {
        SET_SHARED_FILES(state, value) {
            state.sharedFiles = value;
        },
        SET_SELECTED_FILES(state, value) {
            state.selectedFiles = value;
        },
        SET_FILES(state, value) {
            state.files = value;
        },
        SET_USER_FILES(state, value) {
            state.userFiles = value;
        },
        SET_USERS_SELECTED(state, value) {
            state.usersSelected = value;
        },
        SET_USERS(state, value) {
            state.users = value;
        },
    },
    actions: {
        getData({commit}) {
            commit('loading/SET_LOADING', true, {root: true});
            API.get('/api/files')
                .then(response => {
                    commit('SET_USER_FILES', response.data.user_files);
                    commit('SET_SHARED_FILES', response.data.shared_files);
                    commit('loading/SET_LOADING', false, {root: true});
                })
                .catch(e => {
                    console.log(e.response)
                    commit('loading/SET_LOADING', false, {root: true});
                });
        },
        saveData({commit, state, dispatch}) {
            commit('loading/SET_LOADING', true, {root: true});
            const formData = new FormData();
            state.files.forEach((file) => {
                formData.append('files[]', file);
            });
            API.post('/api/files/store-files', formData, {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            })
                .then(() => {
                    commit('snackbar/SET_SNACKBAR_OPENED', true, {root: true});
                    commit('snackbar/SET_MESSAGE', 'Saved successfully', {root: true});
                    commit('SET_FILES', []);
                    commit('loading/SET_LOADING', false, {root: true});
                    dispatch('getData');
                })
                .catch(e => {
                    console.log(e.response);
                    commit('loading/SET_LOADING', false, {root: true});
                });
        },
        async downloadFiles({commit}, payload) {
            commit('loading/SET_LOADING', true, { root: true });

            await Promise.all(
                payload.fileIds.map(async (fileId, index) => {
                    try {
                        const res = await API.post('/api/files/download-file', {
                            fileId: fileId,
                        }, {
                            responseType: 'arraybuffer',
                        });

                        const blob = new Blob([res.data]);
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = payload.filenames[index];
                        link.click();
                        link.remove();
                    } catch (error) {
                        console.log(error.response);
                    }
                })
            );
            commit('loading/SET_LOADING', false, { root: true });
        },
        deleteFiles({commit, dispatch}, fileIds) {
            commit('loading/SET_LOADING', true, {root: true});
            API.post('/api/files/delete-files',{
                fileIds: fileIds,
            })
                .then(res => {
                    commit('snackbar/SET_SNACKBAR_OPENED', true, {root: true});
                    commit('snackbar/SET_MESSAGE', res.data.message, {root: true});
                    commit('SET_FILES', []);
                    commit('loading/SET_LOADING', false, {root: true});
                    dispatch('getData');
                })
                .catch(e => {
                    console.log(e.response);
                    commit('loading/SET_LOADING', false, {root: true});
                });

            commit('loading/SET_LOADING', true, { root: true });
        },
        shareFiles({commit, dispatch}, payload) {
            commit('loading/SET_LOADING', true, {root: true});
            API.post('/api/files/share', payload)
                .then(res => {
                    commit('snackbar/SET_SNACKBAR_OPENED', true, {root: true});
                    commit('snackbar/SET_MESSAGE', res.data.message, {root: true});
                    commit('loading/SET_LOADING', false, {root: true});
                    dispatch('getData');
                })
                .catch(e => {
                    console.log(e.response);
                    commit('loading/SET_LOADING', false, {root: true});
                });
        },
        getUsers({commit}) {
            commit('loading/SET_LOADING', true, {root: true});
            API.post('/api/files/get-users')
                .then(res => {
                    commit('loading/SET_LOADING', false, {root: true});
                    commit('SET_USERS', res.data);
                })
                .catch(e => {
                    console.log(e.response);
                    commit('loading/SET_LOADING', false, {root: true});
                });
        }
    }
}
