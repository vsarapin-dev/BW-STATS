import API from "../../../api";

export default {
    state: () => ({
        mmrWinrate: null,
    }),
    getters: {
        mmrWinrate: state => state.mmrWinrate,
    },
    mutations: {
        SET_MMR_WINRATE(state, payload) {
            if (Array.isArray(payload)) {
                state.mmrWinrate = payload;
            } else {
                state.mmrWinrate = payload;
            }
        },
        RESET_STATE(state) {
            state.mmrWinrate = null;
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/mmr-winrate', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('mmr_winrate') &&
                        res.data.mmr_winrate !== null) {
                        commit('SET_MMR_WINRATE', res.data.mmr_winrate);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
