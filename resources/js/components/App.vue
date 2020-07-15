<template>
    <div>
        <header>
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo"
                        v-html="app_name"></a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                    </ul>
                </div>
            </nav>
        </header>

        <main>
            <div class="container">
                <router-view></router-view>
            </div>
        </main>

        <footer>

        </footer>
    </div>
</template>

<script>

    import M from 'materialize-css'

    export default {

        data () {
            return {
                app_name: "",
            }
        },

        mounted() {
            M.AutoInit();

            this.app_name = process.env.MIX_APP_NAME;
        },

        methods: {

        }
    }
</script>

<style scoped>

</style>
