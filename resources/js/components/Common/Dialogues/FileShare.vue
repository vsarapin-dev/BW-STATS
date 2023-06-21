<template>
    <v-row justify="center">
        <v-dialog
            v-model="fileShareVisible"
            transition="dialog-top-transition"
            persistent
            max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">Share</span>
                </v-card-title>

                <v-card-text>
                    <v-autocomplete
                        v-model="usersSelected"
                        :items="users"
                        item-text="name"
                        item-value="id"
                        outlined
                        dense
                        chips
                        small-chips
                        label="Share with"
                        multiple
                    ></v-autocomplete>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="error darken-1" text @click="closeFileShare">Close</v-btn>
                    <v-btn color="success darken-1" text @click="share">Share</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "FileShare",
    computed: {
        fileShareVisible: {
            get() { return this.$store.getters['dialogVisibility/isOpenedFilesShareDialog'] },
            set(value) { this.$store.commit('dialogVisibility/SET_FILES_SHARE_DIALOG_VISIBILITY', value) }
        },
        usersSelected: {
            get() { return this.$store.getters['files/usersSelected'] },
            set(value) { this.$store.commit('files/SET_USERS_SELECTED', value) }
        },
        users: {
            get() { return this.$store.getters['files/users'] },
            set(value) { this.$store.commit('files/SET_USERS', value) }
        },
        selectedFiles: {
            get() { return this.$store.getters['files/selectedFiles'] },
            set(value) { this.$store.commit("files/SET_SELECTED_FILES", value) }
        },
    },
    mounted() {
        this.$store.dispatch('files/getUsers');
    },
    methods: {
        closeFileShare() {
            this.usersSelected = [];
            this.selectedFiles = [];
            this.users = [];
            this.fileShareVisible = false;
        },
        share() {
            this.$store.dispatch('files/shareFiles', {
                fileIds: this.selectedFiles.map(i => i.id),
                userIds: this.usersSelected
            });
            this.closeFileShare();
        }
    },
}
</script>

<style scoped>

</style>
