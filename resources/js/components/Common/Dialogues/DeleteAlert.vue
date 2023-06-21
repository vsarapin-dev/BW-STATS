<template>
    <v-row justify="center">
        <v-dialog
            v-model="deleteAlertVisible"
            transition="dialog-top-transition"
            persistent
            max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">Delete</span>
                </v-card-title>

                <v-card-text>
                    Are you sure you want to delete this?
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="error darken-1" text @click="closeDeleteAlert">Close</v-btn>
                    <v-btn color="success darken-1" text @click="deleteApproved">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "DeleteAlert",
    computed: {
        deleteAlertVisible: {
            get() { return this.$store.getters['dialogVisibility/isOpenedDeleteAlertDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_DELETE_ALERT_DIALOG_VISIBILITY', value) }
        },
    },
    methods: {
        closeDeleteAlert() {
            this.deleteAlertVisible = false;
        },
        deleteApproved() {
            this.$store.commit('dialogVisibility/SET_DELETE_APPROVED', true);
            this.closeDeleteAlert();
        }
    },
}
</script>

<style scoped>

</style>
