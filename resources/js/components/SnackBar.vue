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
    props: ['visible', 'message'],
    computed: {
        messageText: {
            get() {
                if (this.message.length === 0)
                {
                    return 'Saved successfully';
                }
                return this.message;
            },
            set(value) {
                this.message = value;
            },
        },
        show: {
            get() {
                return this.visible
            },
            set(value) {
                if (!value) {
                    this.$emit('close')
                }
            }
        }
    },
}
</script>
