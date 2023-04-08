<template>
    <v-layout row wrap align-center>
        <v-flex>
            <v-row justify="center" align="center">
                <v-card elevation=5 width="700">
                    <v-card-title>
                        <span class="text-h5 ml-10">Sign Up</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-form ref="form" v-model="valid" lazy-validation>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="name"
                                            prepend-icon="mdi-account"
                                            type="text"
                                            label="Login"
                                            required
                                        ></v-text-field>
                                    </v-col>
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
                                            :rules="passwordRules"
                                            :error-messages="passwordError.length > 0 ? [passwordError] : []"
                                            required
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="confirmPassword"
                                            prepend-icon="mdi-lock"
                                            label="Password"
                                            type="password"
                                            :rules="confirmPasswordRules.concat(passwordConfirmationRule)"
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
                            @click="login"
                        >
                            To login page
                        </v-btn>
                        <v-btn
                            color="success darken-1"
                            text
                            @click="registration"
                        >
                            Register
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-row>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    name: 'Registration',
    data() {
        return {
            valid: true,
            name: "",
            email: "",
            password: "",
            confirmPassword: "",
            nameRules: [
                v => !!v || "Name is required",
                v => (v && v.length <= 10) || "Name must be less than 10 characters"
            ],
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            passwordRules: [v => !!v || "Password is required"],
            confirmPasswordRules: [v => !!v || "Password is required"],
            emailError: "",
            passwordError: "",
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
            this.$router.push({ name: 'login' });
        },
        registration() {
            if (this.validate()) {
                this.resetValidation();
                this.resetErrorMessages();

                axios.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.confirmPassword,
                })
                    .then(res => {
                        this.$router.push({name: 'login'});
                    })
                    .catch(e => {
                        this.checkErrors(e);
                    })
            }
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
        }
    },
    computed: {
        passwordConfirmationRule() {
            return () =>
                this.password === this.confirmPassword || "Password must match";
        },
    }

}
</script>
