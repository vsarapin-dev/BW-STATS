import API from "../../../api";

export default {
    state: () => ({
        files: [],
        userFiles: [],
    }),
    getters: {
        files: state => state.files,
        userFiles: state => state.userFiles,
    },
    mutations: {
        SET_FILES(state, value) {
            state.files = value;
        },
        SET_USER_FILES(state, value) {
            state.userFiles = value;
        }
    },
    actions: {
        getData({commit}) {
            commit('loading/SET_LOADING', true, {root: true});
            API.get('/api/files')
                .then(response => {
                    commit('SET_USER_FILES', response.data.user_files);
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
                .then(() => {
                    commit('snackbar/SET_SNACKBAR_OPENED', true, {root: true});
                    commit('snackbar/SET_MESSAGE', 'Deleted successfully', {root: true});
                    commit('SET_FILES', []);
                    commit('loading/SET_LOADING', false, {root: true});
                    dispatch('getData');
                })
                .catch(e => {
                    console.log(e.response);
                    commit('loading/SET_LOADING', false, {root: true});
                });

            commit('loading/SET_LOADING', true, { root: true });
        }
    }
}
