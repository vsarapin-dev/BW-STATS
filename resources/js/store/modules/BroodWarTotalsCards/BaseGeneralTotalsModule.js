import API from "../../../api";

export default {
    state: () => ({
        generalStats: null,
    }),
    getters: {
        generalStats: state => state.generalStats,
    },
    mutations: {
        SET_GENERAL_STATS(state, payload) {
            state.generalStats = payload;
        },
        RESET_STATE(state) {
            state.generalStats = null;
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/general-stats', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('general_stats') &&
                        res.data.generalStats !== null) {
                        commit('SET_GENERAL_STATS', res.data.general_stats);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
