<template>
    <div class="page">
        <navigation class="has-shadow"></navigation>
        <div class="section">
            <div class="container is-fluid">
                <h1 class="title is-1 has-text-weight-light" v-t="'profile'"></h1>
                <h4 class="subtitle is-4" v-t="'profile_subtitle'"></h4>
                <div class="tabs">
                    <ul>
                        <router-link v-for="item in tabs"
                                     :key="item.path"
                                     tag="li"
                                     :to="item.path">
                            <a v-t="item.label"></a>
                        </router-link>
                    </ul>
                </div>
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            const tabs = [
                { path: '/profile', label: 'account', },
                { path: '/profile/security', label: 'security', },
            ];
            return {
                tabs,
            };
        },
        computed: {
            user() {
                return this.$store.getters.user;
            },
        }
    }
</script>
