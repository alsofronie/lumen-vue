<template>
    <div class="columns">
        <div class="column is-half">
            <form @submit.prevent="save" @keydown="form.clear($event.target.id)">
                <div class="field">
                    <label for="name" class="label" v-t="'name'"></label>
                    <div class="control">
                        <input type="text" class="input is-large" v-model="form.name" id="name" />
                    </div>
                    <div class="help is-danger" v-if="form.has('name')">{{ form.error('name') }}</div>
                </div>
                <div class="field">
                    <label for="email" class="label" v-t="'email'"></label>
                    <div class="control">
                        <input type="email" class="input is-large" v-model="form.email" id="email" />
                    </div>
                </div>
                <button type="submit" class="button is-primary is-large is-fullwidth" :class="{'is-loading': isSaving}" v-t="'save'"></button>
            </form>
        </div>
    </div>
</template>

<script>
    import Form from "../lib/form";

    export default {
        mounted() {
            this.isLoading = true;
            this.$http.get('/profile')
                .then(response => {
                    this.isLoading = false;
                    this.form.email = response.data.user.email;
                    this.form.name = response.data.user.name;
                })
                .catch(err => {
                    this.bus.$emit('srv-err', err);
                });
        },
        data() {
            return {
                isLoading: false,
                isSaving: false,
                form: new Form({
                    email: '',
                    name: '',
                }),
            };
        },
        methods: {
            save() {
                this.isSaving = true;
                this.$http.put('/profile', this.form.data)
                    .then(response => {
                        this.isSaving = false;
                        this.$store.commit('user', response.data.user);
                        this.bus.$emit('notify', {theme: 'is-success', text: this.$t('update_success')});
                    })
                    .catch(err => {
                        this.isSaving = false;
                        if (!this.form.parse(err)) {
                            this.bus.$emit('srv-err', err);
                        }
                    });
            }
        },
    }
</script>
