<template>
    <section class="page-container-full" v-if="page">
        <div class="page-hero">
            <div class="fluid">
                <h1>{{page.title}}</h1>
                <h2>{{page.excerpt}}</h2>
            </div>
        </div>
        <div class="fluid">
            <article class="page-article" v-html="page.body">
            </article>
        </div>
    </section>
</template>

<script>
    import {setTitle} from "@/helpers/utils"

    export default {
        name: "Page",
        computed: {
            /**
             * @return {IPage}
             */
            page(){
                let slug = this.$route.params.slug;
                let pages = this.$store.getters['app/pages'];
                return pages[slug] || null;
            }
        },
        mounted() {
            let pages = this.$store.getters['app/pages'];
            if (!pages)
                return null
            let slug = this.$route.params.slug;
            if (!slug || !pages[slug])
                this.$router.push({name: 'Page404'})

            setTitle(this.page.title || null)
        }
    }
</script>

<style scoped>

</style>