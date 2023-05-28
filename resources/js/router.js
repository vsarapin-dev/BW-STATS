import Vue from "vue";
import VueRouter from "vue-router";
import Login from "./components/Auth/Login"
import Registration from "./components/Auth/Registration"
import GameStatsTable from "./components/BroodWar/DataTables/GameStatsTable"
import ExcelImport from "./components/BroodWar/ExcelImport"

const router = new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/sign-in', component: Login,
            name: 'login',
        },
        {
            path: '/sign-up', component: Registration,
            name: 'register',
        },
        {
            path: '/game-stats', component: GameStatsTable,
            name: 'stats',
        },
        {
            path: '/file-import', component: ExcelImport,
            name: 'import',
        }
    ]
})

Vue.use(VueRouter);

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('access_token');

    if (!token)
    {
        if (to.name === 'login' || to.name === 'register') {
            return next();
        } else {
            return next({
                name: 'login'
            })
        }
    }

    if (to.name === 'login' || to.name === 'register' || to.name === null && token) {
        return next({
            name: 'stats'
        })
    }
    else {
        return next();
    }
});

export default router;
