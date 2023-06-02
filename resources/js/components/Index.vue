<template>
    <v-app class="fill-height" data-app>
        <Loader v-if="$store.getters['loading/loading']" />
        <SnackBar></SnackBar>

        <v-navigation-drawer
            v-if="access_token"
            v-model="drawer"
            absolute
            temporary
        >
            <v-list-item>
                <v-list-item-avatar>
                    <v-img src="/img/icon.png"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>{{ login }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense>
                <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    :to="item.path"
                    link
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app>
            <v-icon v-if="access_token" @click="drawer = !drawer">mdi-menu</v-icon>
            <v-spacer></v-spacer>
            <v-icon v-if="access_token" @click="logout">mdi-logout</v-icon>
        </v-app-bar>

        <v-main>
            <v-container :class="{ 'fill-height': access_token === null }">
                <router-view :key="$route.fullPath"></router-view>
            </v-container>
        </v-main>


        <v-footer app>
            <!-- -->
        </v-footer>

        <CreateNewGameStatRowDialogue
            ref="createNewGameStatRowDialogue"
            :visible="isOpenedCreateNewDialog"
            :headerName="$store.getters['dialogVisibility/dialogHeaderName']"
            :updateRow="$store.getters['dialogVisibility/shouldUpdateTableRow']"
            @close="isOpenedCreateNewDialog = false; shouldUpdateRow = false"
        />
        <FilterGameStartDialog
            :filterVisible="isOpenedFilterDialog"
            :seasonId="$store.getters['seasons/selectedSeason']"
            @close="isOpenedFilterDialog = false"
        />


    </v-app>
</template>

<script>
import API from "../api";
import Loader from "./Common/Loader";
import SnackBar from "./Common/SnackBar";
import anime from 'animejs';
import CreateNewGameStatRowDialogue from "./BroodWar/Dialogues/CreateNewGameStatRowDialogue";
import FilterGameStartDialog from "./BroodWar/Dialogues/FilterGameStatDialogue";

export default {
    name: "Index",
    data() {
        return {
            access_token: null,
            login: null,
            drawer: false,
            items: [
                {title: 'Import', icon: 'mdi-file-arrow-left-right-outline', 'path': '/file-import'},
                {title: 'Home', icon: 'mdi-view-dashboard', 'path': '/game-stats'},
            ],
        }
    },
    components: {
        Loader,
        SnackBar,
        CreateNewGameStatRowDialogue,
        FilterGameStartDialog,
    },
    mounted() {
        this.getLogin();
        this.getToken();
        this.animateDrawer();
    },
    watch: {
        drawer(value) {
            this.animateDrawer(); // Запуск анимации при изменении значения drawer
        },
    },
    computed: {
        isOpenedCreateNewDialog: {
            get() { return this.$store.getters['dialogVisibility/isOpenedCreateNewDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_CREATE_NEW_DIALOG_VISIBILITY', value) },
        },
        isOpenedFilterDialog: {
            get() { return this.$store.getters['dialogVisibility/isOpenedFilterDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_FILTER_DIALOG_VISIBILITY', value) },
        },
    },
    methods: {
        animateDrawer() {
            this.$nextTick(() => {
                const drawerContent = this.$el.querySelector('.v-navigation-drawer__content');

                if (this.drawer) {
                    // Показать выдвигающееся меню
                    anime({
                        targets: drawerContent,
                        translateX: 0,
                        duration: 500,
                        easing: 'easeOutQuad',
                        autoplay: true,
                    });
                } else {
                    // Скрыть выдвигающееся меню
                    anime({
                        targets: drawerContent,
                        translateX: -300,
                        duration: 500,
                        easing: 'easeOutQuad',
                        autoplay: true,
                    });
                }
            });
        },
        getLogin() {
            this.login = localStorage.getItem('bw_stats_login');
        },
        getToken() {
            this.access_token = localStorage.getItem('access_token');
        },
        logout() {
            API.post('/api/auth/logout')
                .then(res => {
                    localStorage.removeItem('access_token');
                    if (this.$route.path !== '/sign-in') {
                        window.location.href = "/sign-in";
                    }
                })
        },
    }
}
</script>
