<template>
    <div id="system-messages">
        <div class="notification" :class="notification.theme" v-for="notification in messages">
            <button type="button" class="delete" @click="removeNotification(notification)"></button>
            <span v-html="notification.message"></span>
        </div>
    </div>
</template>

<style>
    #system-messages {
        position: fixed;
        left: 50%;
        top: 1rem;
        width: 280px;
        margin-left: -140px;
        z-index: 999;
    }
</style>

<script>
    import bus from './../lib/bus';

    export default {
        mounted() {
            bus.$on('server-error', this.onServerError);
            bus.$on('notify', this.onNotify);
        },
        data() {
            return {
                messages: [],
                tk: null,
            }
        },
        methods: {
            onNotify(error) {
                this.messages.push({
                    key: Math.floor(100000 * Math.random()),
                    theme: error.theme,
                    message: error.text,
                });
                this.startTicking();
            },
            onServerError(err) {
                let notification = {
                    key: Math.floor(100000 * Math.random()),
                    theme: 'is-danger',
                    message: this.$t('server_error'),
                };

                if (err.response && err.response.data && err.response.data.message) {
                    notification.message = err.response.data.message;
                } else if (err.response && err.response.statusText) {
                    notification.message = err.response.statusText;
                }

                this.messages.push(notification);
                this.startTicking();
            },
            startTicking() {
                clearTimeout(this.tk);
                this.tk = setTimeout(() => {
                    if (this.messages.length > 0) {
                        this.messages.splice(0, 1);
                    }

                    if (this.messages.length > 0) {
                        this.startTicking();
                    }
                }, 3000);
            },
            removeNotification(notification) {
                clearTimeout(this.tk);
                let idx = this.messages.indexOf(notification);
                if (idx >= 0) {
                    this.messages.splice(idx, 1);
                    this.startTicking();
                }
            }
        }
    }
</script>
