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
                    <v-row class="justify-center">
                        <v-btn color="success" :disabled="files.length === 0" block @click="saveFiles">Save</v-btn>
                        <v-btn class="mt-1" color="primary" :disabled="selectedFilesToDownload.length === 0" block @click="downloadFiles">Download</v-btn>
                        <v-btn class="mt-1" color="error" :disabled="selectedFilesToDownload.length === 0" block @click="deleteFiles">Delete</v-btn>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-row>
        <v-row class="justify-center">
            <template v-for="(group, index) in userFilesGrouped">
                <v-card class="mt-3 mx-1" width="260" :key="index">
                    <v-list>
                        <v-list-item-group
                            v-model="selectedFilesToDownload"
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
    data() {
        return {
            selectedFilesToDownload: [],
        }
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
        files: {
            get() { return this.$store.getters['files/files'] },
            set(value) { this.$store.commit("files/SET_FILES", value) }
        },
        userFiles: {
            get() { return this.$store.getters['files/userFiles'] },
            set(value) { this.$store.commit("files/SET_USER_FILES", value) }
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
                fileIds: this.selectedFilesToDownload.map(i => i.id),
                filenames: this.selectedFilesToDownload.map(i => i.name),
            });
            this.selectedFilesToDownload = [];
        },
        deleteFiles() {
            this.$store.dispatch("files/deleteFiles", this.selectedFilesToDownload.map(i => i.id));
            this.selectedFilesToDownload = [];
        }
    }
}
</script>

<style scoped>
>>> .v-input__prepend-outer {
    display: none !important;
}
</style>
