import API from "../../../api";

export default {
    state: () => ({
        mapsWinrate: null,
    }),
    getters: {
        mapsWinrate: state => state.mapsWinrate,
    },
    mutations: {
        SET_MAPS_WINRATE(state, payload) {
            if (Array.isArray(payload)) {
                state.mapsWinrate = payload;
            } else {
                state.mapsWinrate = payload;
            }
        },
        RESET_STATE(state) {
            state.mapsWinrate = null;
        }
    },
    actions: {
        getData({commit}, seasonId) {
            API.post('/api/auth/maps-winrate', {
                season_id: seasonId,
            })
                .then(res => {
                    if (res.data.hasOwnProperty('maps_winrate') &&
                        res.data.maps_winrate !== null) {
                        commit('SET_MAPS_WINRATE', res.data.maps_winrate);
                    }
                })
                .catch(err => {
                    console.log(err.response);
                })
        }
    }
}
