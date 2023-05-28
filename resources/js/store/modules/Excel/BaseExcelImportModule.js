import API from "../../../api";

export default {
    state: () => ({
        file: null,
        seasons: null,
        selectedSeason: null,
    }),
    getters: {
        file: state => state.file,
        seasons: state => state.seasons,
        selectedSeason: state => state.selectedSeason,
    },
    mutations: {
        SET_FILE(state, file) {
            state.file = file;
        },
        SET_SELECTED_SEASON(state, season) {
            state.selectedSeason = season;
        },
        SET_SEASONS(state, seasons) {
            state.seasons = seasons;
        },
    },
    actions: {
        getSeasons({state, commit}) {
            API.post('/api/auth/seasons')
                .then(res => {
                    commit('SET_SEASONS', res.data.seasons.map(i => i.season));
                    commit('SET_SELECTED_SEASON', localStorage.hasOwnProperty('selectedSeason') ?
                        state.seasons[parseInt(localStorage.getItem('selectedSeason')) - 10]:
                        state.seasons[0]);
                })
                .catch(e => {
                    console.log(e)
                })
        },
        importFile({state, commit}) {
            if (state.file === null) return;
            commit('loading/SET_LOADING', true, { root: true });

            let formData = new FormData();
            formData.append('excel_file', state.file);
            formData.append('season_number', state.selectedSeason);
            API.post('/api/auth/import-excel-file', formData)
                .then(res => {
                    commit('loading/SET_LOADING', false, { root: true });
                    commit('snackbar/SET_SNACKBAR_OPENED', true, { root: true });
                    commit('SET_FILE', null);
                })
                .catch(e => {
                    commit('loading/SET_LOADING', false, { root: true });
                    console.log(e.response)
                })
        },
    }
}
