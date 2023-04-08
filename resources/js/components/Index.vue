<template>
    <v-app class="fill-height" data-app>
        <v-navigation-drawer
            v-if="access_token"
            v-model="drawer"
            absolute
            temporary
        >
            <v-list-item>
                <v-list-item-avatar>
                    <v-img src="https://randomuser.me/api/portraits/men/78.jpg"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>John Leider</v-list-item-title>
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
    </v-app>
</template>

<script>
import API from "../api";

export default {
    name: "Index",
    data() {
        return {
            access_token: null,
            drawer: false,
            items: [
                {title: 'Import', icon: 'mdi-file-arrow-left-right-outline', 'path': '/file-import'},
                {title: 'Home', icon: 'mdi-view-dashboard', 'path': '/game-stats'},
            ],
        }
    },
    mounted() {
        this.getToken();
    },
    methods: {
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
