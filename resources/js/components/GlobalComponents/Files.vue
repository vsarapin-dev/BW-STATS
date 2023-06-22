<template>
    <v-container>
        <v-row class="justify-center">
            <v-card width="700">
                <v-card-text>
                    <v-row class="justify-center">
                        <v-file-input
                            v-model="files"
                            color="deep-purple accent-4"
                            counter
                            label="File input"
                            multiple
                            placeholder="Select your files"
                            outlined
                            :show-size="1000"
                        >
                            <template v-slot:selection="{ index, text }">
                                <v-chip
                                    v-if="index < 2"
                                    color="deep-purple accent-4"
                                    dark
                                    label
                                    small
                                >
                                    {{ text }}
                                </v-chip>

                                <span
                                    v-else-if="index === 2"
                                    class="text-overline grey--text text--darken-3 mx-2"
                                >
                        +{{ files.length - 2 }} File(s)
                      </span>
                            </template>
                        </v-file-input>
                    </v-row>
                    <v-col>
                        <v-row>
                            <v-col class="xs-12 px-0">
                                <v-btn color="success" :disabled="files.length === 0" block @click="saveFiles">Save</v-btn>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="xs-12 md-6 pa-0 mr-1">
                                <v-btn class="mt-1" color="primary" :disabled="selectedFiles.length === 0" block @click="downloadFiles">Download</v-btn>
                            </v-col>
                            <v-col class="xs-12 md-6 pa-0 ml-1">
                                <v-btn class="mt-1" color="error" :disabled="selectedFiles.length === 0" block @click="deleteFiles">Delete</v-btn>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="xs-12 px-0">
                                <v-btn color="success" :disabled="selectedFiles.length === 0" block @click="shareFiles">Share</v-btn>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-card-text>
            </v-card>
        </v-row>
        <v-row class="justify-center">
            <template v-for="(group, index) in userFilesGrouped">
                <v-card class="mt-3 mx-1" width="260" :key="index">
                    <v-list>
                        <v-list-item-group
                            v-model="selectedFiles"
                            multiple
                            color="indigo"
                        >
                            <v-list-item v-for="(item, i) in group" :key="i" :value="item">
                                <v-list-item-content>
                                    <a href="#">
                                        <v-list-item-title v-text="item.name" class="text-subtitle-1" />
                                    </a>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </template>
        </v-row>
        <v-divider v-if="sharedFilesGrouped.length > 0" class="mt-6"></v-divider>
        <v-row v-if="sharedFilesGrouped.length > 0" class="justify-center my-2">
            <span class="text-uppercase light-green--text">shared files</span>
        </v-row>
        <v-row class="justify-center">
            <template v-for="(group, index) in sharedFilesGrouped">
                <v-card class="mx-1" width="260" :key="index">
                    <v-list>
                        <v-list-item-group
                            v-model="selectedFiles"
                            multiple
                            color="indigo"
                        >
                            <v-list-item v-for="(item, i) in group" :key="i" :value="item">
                                <v-list-item-content>
                                    <a href="#">
                                        <v-list-item-title v-text="item.name" class="text-subtitle-1" />
                                    </a>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </template>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "Files",
    watch: {
        "$store.state.dialogVisibility.deleteApproved": {
            handler: function (value) {
                if (value === true) {
                    this.deleteFiles(true);
                }
            },
        },
    },
    computed: {
        userFilesGrouped() {
            const groupSize = 10;
            const groups = [];
            for (let i = 0; i < this.userFiles.length; i += groupSize) {
                groups.push(this.userFiles.slice(i, i + groupSize));
            }
            return groups;
        },
        sharedFilesGrouped() {
            const groupSize = 10;
            const groups = [];
            for (let i = 0; i < this.sharedFiles.length; i += groupSize) {
                groups.push(this.sharedFiles.slice(i, i + groupSize));
            }
            return groups;
        },
        selectedFiles: {
            get() { return this.$store.getters['files/selectedFiles'] },
            set(value) { this.$store.commit("files/SET_SELECTED_FILES", value) }
        },
        files: {
            get() { return this.$store.getters['files/files'] },
            set(value) { this.$store.commit("files/SET_FILES", value) }
        },
        userFiles: {
            get() { return this.$store.getters['files/userFiles'] },
            set(value) { this.$store.commit("files/SET_USER_FILES", value) }
        },
        sharedFiles: {
            get() { return this.$store.getters['files/sharedFiles'] },
            set(value) { this.$store.commit("files/SET_SHARED_FILES", value) }
        }
    },
    mounted() {
        this.$store.dispatch("files/getData");
    },
    methods: {
        saveFiles() {
            this.$store.dispatch("files/saveData");
        },
        downloadFiles() {
            this.$store.dispatch("files/downloadFiles", {
                fileIds: this.selectedFiles.map(i => i.id),
                filenames: this.selectedFiles.map(i => i.name),
            });
            this.selectedFiles = [];
        },
        deleteFiles(approved) {
            if (approved === true) {
                this.$store.commit("dialogVisibility/SET_DELETE_APPROVED", false);
                this.$store.dispatch("files/deleteFiles", this.selectedFiles.map(i => i.id));
                this.selectedFiles = [];
            } else {
                this.$store.commit("dialogVisibility/SET_DELETE_ALERT_DIALOG_VISIBILITY",true);
            }

        },
        shareFiles() {
            this.$store.dispatch("files/getUsers");
            this.$store.commit("dialogVisibility/SET_FILES_SHARE_DIALOG_VISIBILITY", true);
        }
    }
}
</script>

<style scoped>
>>> .v-input__prepend-outer {
    display: none !important;
}
</style>
