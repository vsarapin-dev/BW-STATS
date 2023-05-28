import API from "../../../api";

export default {
    state: () => ({
        availableSeasons: [],
        selectedSeason: null,
    }),
    getters: {
        availableSeasons: state => state.availableSeasons,
        selectedSeason: state => state.selectedSeason,
    },
    mutations: {
        SET_AVAILABLE_SEASONS(state, payload) {
            state.availableSeasons = payload;
        },
        SET_SELECTED_SEASON(state, payload) {
            state.selectedSeason = payload;
        },
        RESET_STATE(state) {
            state.availableSeasons = [];
            state.selectedSeason = null;
        }
    },
    actions: {
        getData({commit}) {
            API.post('/api/auth/available-seasons')
                .then(res => {
                    if (res.data.hasOwnProperty('available_seasons') &&
                        res.data.hasOwnProperty('selected_season') &&
                        res.data.available_seasons !== null &&
                        res.data.selected_season !== null
                    ) {
                        commit('SET_AVAILABLE_SEASONS', res.data.available_seasons);
                        commit('SET_SELECTED_SEASON', res.data.selected_season.id);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
