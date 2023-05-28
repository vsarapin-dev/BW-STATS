import API from "../../../api";

export default {
    state: () => ({
        bestMaps: [],
    }),
    getters: {
        bestMaps: state => state.bestMaps,
    },
    mutations: {
        SET_BEST_MAPS(state, payload) {
            if (Array.isArray(payload)) {
                state.bestMaps = payload;
            } else {
                state.bestMaps = payload;
            }
        },
        RESET_STATE(state) {
            state.bestMaps = [];
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/best-maps', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('best_maps') &&
                        res.data.best_maps !== null) {
                        commit('SET_BEST_MAPS', res.data.best_maps);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
