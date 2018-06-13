<template>
    <div class="hero is-fullheight is-info" id="page-register">
        <div class="hero-body">
            <div class="container">
                <form @submit.prevent="doRegister" @keydown="form.clear($event.target.id)">
                    <div class="columns is-centered">
                        <div class="column is-6-tablet is-6-desktop is-4-fullhd">
                            <div class="box is-radiusless">
                                <div class="field">
                                    <label class="label" v-t="'name'" for="name"></label>
                                    <div class="control">
                                        <input type="text" id="name" v-model="form.name" class="input is-large" />
                                    </div>
                                    <div class="help is-danger" v-if="form.has('name')" v-text="form.error('name')"></div>
                                </div>
                                <div class="field">
                                    <label class="label" v-t="'email'" for="email"></label>
                                    <div class="control">
                                        <input type="email" id="email" v-model="form.email" class="input is-large" />
                                    </div>
                                    <div class="help is-danger" v-if="form.has('email')" v-text="form.error('email')"></div>
                                </div>
                                <div class="field">
                                    <label class="label" for="password" v-t="'password'"></label>
                                    <div class="control">
                                        <input type="password" id="password" v-model="form.password" class="input is-large" />
                                    </div>
                                    <div class="help is-danger" v-if="form.has('password')" v-text="form.error('password')"></div>
                                </div>
                                <div class="field">
                                    <label class="label" for="password_confirm" v-t="'password_confirm'"></label>
                                    <div class="control">
                                        <input type="password" id="password_confirm" v-model="password_confirm" class="input is-large" />
                                    </div>
                                </div>
                                <button type="submit"
                                        :disabled="!canRegister"
                                        class="button is-primary is-large is-fullwidth" v-t="'register'"></button>
                            </div>
                            <div class="content">
                                <p class="has-text-centered">
                                    <router-link to="/login">{{ $t('login') }}</router-link>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from './../lib/form';
    import prefs from './../lib/preferences';

    export default {
        data() {
            return {
                form: new Form({
                    email: '',
                    password: '',
                    name: '',
                }),
                password_confirm: '',
            };
        },
        mounted() {
            prefs.token = null;
            this.$store.commit('logout');
        },
        computed: {
            canRegister() {
                return (
                    this.form.name.length > 0 &&
                    this.form.email.length > 0 &&
                    this.form.password.length > 0 &&
                    this.form.password === this.password_confirm
                );
            }
        },
        methods: {
            doRegister() {
                let data = this.form.data;
                this.$http.post('/register', data).then(response => {
                    prefs.token = response.data.token;
                    setTimeout(() => {
                        // we will reboot Vue with the new token and everything.
                        this.$destroy();
                        let href = window.location.href;
                        // Need to change this if the router iw not using hash method
                        href = href.replace(window.location.hash, '');
                        window.location.replace(href);
                    }, 10);
                }).catch(err => {
                    if (!this.form.parse(err)) {
                        this.bus.$emit('server-error', err);
                    }
                });
            }
        }
    }
</script>
