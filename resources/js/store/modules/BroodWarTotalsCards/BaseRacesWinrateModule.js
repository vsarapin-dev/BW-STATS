import API from "../../../api";

export default {
    state: () => ({
        racesWinrate: [],
    }),
    getters: {
        racesWinrate: state => state.racesWinrate,
    },
    mutations: {
        SET_RACES_WINRATE(state, payload) {
            if (Array.isArray(payload)) {
                state.racesWinrate = payload;
            } else {
                state.racesWinrate = payload;
            }
        },
        RESET_STATE(state) {
            state.racesWinrate = [];
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/races-winrate', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('races_winrate') &&
                        res.data.races_winrate !== null) {
                        commit('SET_RACES_WINRATE', res.data.races_winrate);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
