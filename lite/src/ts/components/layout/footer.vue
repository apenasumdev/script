<template>
    <footer>
        <div class="wrapper">
            <div class="actions">
                <!--<div id="google_translate_element" class="translator"></div>-->
                <div class="social-links">
                    <a :aria-label="social.text" v-for="(social, index) in socials" :key="index" :href="social.link" target="_blank">
                        <i :class="[social.text === 'mail' ? 'fas':'fab', social.text === 'mail' ? 'fa-envelope-open-text' : `fa-${social.text}`]"></i>
                    </a>
                </div>
            </div>
            <div class="nav">
                <router-link v-for="(link,label) in pages" :key="link" :to="{name: 'page',params: {slug: link}}">
                    {{label}}
                </router-link>
                <span>
                    © {{new Date().getFullYear()}} {{app.name}}
                </span>
            </div>
        </div>
    </footer>
</template>

<script>

    export default {
        name: "Footer",
        data: ()=>({
            pages: {
                'Terms of Services': 'terms-of-services',
                'Privacy Policy': 'privacy-policy'
            }
        }),
        computed: {
            /**
             *
             * @return {IApp}
             */
            app(){
                return this.$store.getters['app/info']
            },
            /**
             *
             * @return {ISocial[]}
             */
            socials(){
                return this.$store.getters['settings/socials']
            }
        }
    }
</script>