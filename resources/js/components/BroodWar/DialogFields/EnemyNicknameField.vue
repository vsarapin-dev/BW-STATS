<template>
    <v-col cols="12" xs="12" md="6">
        <v-text-field label="Enemy nickname" dense v-model="enemyNickname"></v-text-field>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyNickname: {
            get() { return this.$store.getters['dialog/enemyNickname'] },
            set(value) { this.$store.commit('dialog/SET_ENEMY_NICKNAME', value) },
        },
    },
}
</script>