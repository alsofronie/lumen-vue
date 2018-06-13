<template>
    <div class="hero is-fullheight">
        <div class="hero-head">
            <navigation class="is-transparent has-shadow"></navigation>
        </div>
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-header-title">
                                    You are logged in!
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="content">
                                    <p>Welcome {{ user.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            user() {
                return this.$store.getters.user;
            }
        }
    }
</script>
