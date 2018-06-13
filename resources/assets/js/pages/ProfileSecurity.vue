<template>
    <div class="columns">
        <div class="column is-half">
            <form @submit.prevent="save" @keydown="form.clear($event.target.id)">
                <div class="field">
                    <label for="password_old" class="label" v-t="'password_old'"></label>
                    <div class="control">
                        <input type="password" class="input is-large" v-model="form.password_old" id="password_old" />
                    </div>
                </div>
                <div class="field">
                    <label for="password" class="label" v-t="'password_new'"></label>
                    <div class="control">
                        <input type="password" class="input is-large" v-model="form.password" id="password" />
                    </div>
                    <div class="help is-danger" v-if="form.has('password')">{{ form.error('password') }}</div>
                </div>
                <div class="field">
                    <label for="password_confirm" class="label" v-t="'password_confirm'"></label>
                    <div class="control">
                        <input type="password" class="input is-large" v-model="password_confirm" id="password_confirm" />
                    </div>
                </div>

                <button type="submit"
                        class="button is-primary is-large is-fullwidth"
                        :class="{'is-loading': isSaving}"
                        :disabled="!canSave"
                        v-t="'save'"></button>
            </form>
        </div>
    </div>
</template>

<script>
    import Form from "../lib/form";

    export default {
        data() {
            return {
                isSaving: false,
                form: new Form({
                    password_old: '',
                    password: '',
                }),
                password_confirm: '',
            };
        },
        computed: {
            canSave() {
                return (
                    this.form.password_old.length >= 0 &&
                    this.form.password.length > 0 &&
                    this.password_confirm === this.form.password
                );
            },
        },
        methods: {
            save() {
                this.isSaving = true;
                this.$http.put('/profile', this.form.data)
                    .then(() => {
                        this.isSaving = false;
                        this.bus.$emit('notify', {theme: 'is-success', text: this.$t('password_changed')});
                        this.form.password_old = '';
                        this.form.password = '';
                        this.password_confirm = '';
                    })
                    .catch(err => {
                        this.isSaving = false;
                        if (!this.form.parse(err)) {
                            this.bus.$emit('srv-err', err);
                        }
                    });
            }
        }
    }
</script>
