<template>
    <v-col cols="12" xs="12" md="6">
        <v-text-field label="Enemy login" dense v-model="enemyLogin"></v-text-field>
    </v-col>
</template>
<script>
export default {
    computed: {
        enemyLogin: {
            get() { return this.$store.getters[`${this.$store.getters.bwDialogModule}/enemyLogin`] },
            set(value) { this.$store.commit(`${this.$store.getters.bwDialogModule}/SET_ENEMY_LOGIN`, value) },
        },
    },
}
</script>
