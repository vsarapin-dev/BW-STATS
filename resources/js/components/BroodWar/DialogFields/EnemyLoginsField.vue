<template>
    <v-col cols="12" xs="12">
        <v-autocomplete
            v-model="enemyLogin"
            :items="enemyLogins"
            item-text="enemy_login"
            label="Enemy login"
            dense
            :search-input.sync="enemyLoginSearch"
            :loading="findLoginLoader"
        ></v-autocomplete>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyLogins: {
            get() { return this.$store.getters['dialogFilter/enemyLogins'] },
            set(value) { this.$store.commit('dialogFilter/SET_ENEMY_LOGINS', value) }
        },
        enemyLoginSearch: {
            get() { return this.$store.getters['dialogFilter/enemyLoginSearch'] },
            set(value) { this.$store.commit('dialogFilter/SET_ENEMY_LOGIN_SEARCH', value) }
        },
        findLoginLoader: {
            get() { return this.$store.getters['dialogFilter/findLoginLoader'] },
            set(value) { this.$store.commit('dialogFilter/SET_FIND_LOGIN_LOADER', value) }
        },

        enemyLogin: {
            get() { return this.$store.getters['dialogFilter/enemyLogin']},
            set(value) { this.$store.commit('SET_ENEMY_LOGIN', value) },
        },
    },
}
</script>
