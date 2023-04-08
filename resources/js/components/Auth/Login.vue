<template>
    <v-layout row wrap align-center>
        <v-flex>
            <v-row justify="center" align="center">
                <v-card elevation=5 width="700">
                    <v-card-title>
                        <span class="text-h5 ml-10">Sign In</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-form ref="form" v-model="valid" lazy-validation>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="email"
                                            prepend-icon="mdi-email"
                                            label="Email"
                                            type="email"
                                            :rules="emailRules"
                                            :error-messages="emailError.length > 0 ? [emailError] : []"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="password"
                                            prepend-icon="mdi-lock"
                                            label="Password"
                                            type="password"
                                            autocomplete="on"
                                            :rules="passwordRules"
                                            :error-messages="passwordError.length > 0 ? [passwordError] : []"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="success darken-1"
                            text
                            @click="goToRegisterPage"
                        >
                            To registration page
                        </v-btn>
                        <v-btn
                            color="success darken-1"
                            text
                            @click="login"
                        >
                            Login
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-row>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    name: 'Login',
    data() {
        return {
            valid: true,
            email: "",
            emailError: "",
            password: "",
            passwordError: "",
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            passwordRules: [v => !!v || "Password is required"],
        }
    },

    mounted() {
        setTimeout(() => {
            const els = document.querySelectorAll("input:-webkit-autofill")
            els.forEach((el) => {
                const label = el.parentElement.querySelector("label")
                label.classList.add("v-label--active")
            })
        }, 700)
    },

    methods: {
        login() {
            if (this.validate()) {
                this.resetValidation();
                this.resetErrorMessages();

                axios.post('api/auth/login', {email: this.email, password: this.password,})
                    .then(res => {
                        localStorage.setItem('access_token', res.data.access_token);
                        window.location.href = "/game-stats";
                    }).catch(error => {
                    console.log(error.response)
                    this.checkErrors(error);
                })

            }
        },
        goToRegisterPage() {
            this.$router.push({name: 'register'});
        },
        validate() {
            return this.$refs.form.validate();
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
        resetErrorMessages() {
            this.emailError = '';
            this.passwordError = '';
        },
        checkErrors(error) {
            if (error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                Object.keys(error.response.data.errors).map(i => {
                    if (i === 'email') {
                        this.emailError = error.response.data.errors.email[0];
                    }
                    if (i === 'password') {
                        this.passwordError = error.response.data.errors.password[0];
                    }
                })
            }
            this.emailError = "Wrong credentials.";
        }
    },

}
</script>
