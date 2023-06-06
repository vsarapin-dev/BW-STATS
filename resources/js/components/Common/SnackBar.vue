<template>
    <div class="text-center ma-2">
        <v-snackbar
            top
            outlined
            color="success"
            v-model="show"
        >
            {{ messageText }}
            <template v-slot:action="{ attrs }">
                <v-btn
                    color="white"
                    text
                    v-bind="attrs"
                    @click="show = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    name: "SnackBar",
    computed: {
        messageText: {
            get() {
                if (!this.$store.getters['snackbar/message'] ||
                    this.$store.getters['snackbar/message'].length === 0) {

                    return 'Saved successfully';
                }
                return this.$store.getters['snackbar/message'];
            },
            set(value) {
                this.message = value;
            },
        },
        show: {
            get() { return this.$store.getters['snackbar/snackBarOpened'] },
            set(value) { if (!value) {
                this.$store.commit('snackbar/SET_SNACKBAR_OPENED', value)
                this.$store.commit('snackbar/SET_MESSAGE', 'Saved successfully');
            } }
        }
    },
}
</script>
