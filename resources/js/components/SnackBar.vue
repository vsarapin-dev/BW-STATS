<template>
    <div class="text-center ma-2">
        <v-snackbar
            top
            color="success"
            v-model="show"
        >
            {{ text }}
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
    props: ['visible'],
    data() {
        return {
            text: `Saved successfully`,
        }
    },
    computed: {
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
