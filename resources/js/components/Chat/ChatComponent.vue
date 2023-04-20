<template>
    <v-card
        max-width="450"
        height="600"
        class="chat-btn scroll"
    >
        <v-toolbar
            color="cyan"
            dark
        >
            <v-toolbar-title>Chat</v-toolbar-title>
        </v-toolbar>

        <v-list three-line>
            <template v-for="(item, index) in items">
                <v-divider
                    v-if="item.divider"
                    :key="index"
                    :inset="item.inset"
                ></v-divider>

                <v-list-item
                    v-else
                    :key="`${item.title}-${index}`"
                >
                    <v-list-item-avatar>
                        <v-img :src="item.avatar"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title v-html="item.title"></v-list-item-title>
                        <v-list-item-subtitle v-html="item.subtitle"></v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </template>
            <v-list-item>
                <v-textarea
                    v-model="chatMessage"
                    label="Message"
                    no-resize
                    rows="2"
                    @keydown="sendIfEnterPressed"
                ></v-textarea>
            </v-list-item>
        </v-list>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="chat-btn" color="success" @click="show=false">Close</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: "ChatComponent",
    props: ['visible'],
    data() {
        return {
            chatMessage: '',
            items: [
                {
                    avatar: '/img/icon.png',
                    title: 'Brunch this weekend?',
                    subtitle: `<span class="text--primary">Ali Connors</span> &mdash; I'll be in your neighborhood doing errands this weekend. Do you want to hang out?`,
                },
                {divider: true, inset: true},

            ],
        }
    },
    computed: {
        show: {
            get() {
                return this.visible;
            },
            set(value) {
                if (!value) {
                    this.$emit('close');
                }
            },
        },
    },
    methods: {
        sendIfEnterPressed(value) {
            if (value.key === 'Enter') {
                this.showMessageInChat(this.chatMessage, 'Me');
                this.chatMessage = '';
            }
        },
        showMessageInChat(text, sender) {
            this.items.push(
                {
                    avatar: sender === 'Me' ? '/img/me.png' : '/img/icon.png',
                    title: sender,
                    subtitle: text,
                }
            );
            this.items.push(
                {divider: true, inset: true},
            );
        },
    },
}
</script>

<style scoped>
.scroll {
    overflow-y: scroll
}
</style>
