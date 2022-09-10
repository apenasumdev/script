<template>
    <div class="card setting-card">
        <form @submit.prevent="post">
            <h3 class="with-button">
                {{isAdd ? 'Add Page': 'Edit Page'}}
                <button :disabled="process" class="style-none add-button">
                    <template v-if="process">
                        {{isAdd ? 'Publishing': 'Saving'}}
                        <i class="space fas fa-spinner fa-spin"></i>
                    </template>
                    <template v-else>
                        {{isAdd ? 'Publish': 'Save'}}
                        <i class="space fas fa-save"></i>

                    </template>
                </button>
            </h3>
            <div class="form-control">
                <label for="page_title">Title</label>
                <input type="page_title" id="page_title" v-model="page.title" @input="createSlug" placeholder="Enter Page Title" required autofocus>
            </div>
            <div class="form-control mt">
                <label for="page_slug">Slug</label>
                <input :disabled="!isAdd" type="page_slug" id="page_slug" v-model="page.slug" placeholder="Enter Page Slug" required>
            </div>
            <div class="form-control mt">
                <label for="page_excerpt">Excerpt</label>
                <textarea name="page_excerpt" id="page_excerpt" v-model="page.excerpt" required cols="5" rows="4"></textarea>
            </div>
            <div class="form-control mt">
                <label for="page_body">Body</label>
                <vue-editor name="page_body" useCustomImageHandler @image-added="uploadImage" required id="page_body" v-model="page.body"></vue-editor>
            </div>
        </form>
    </div>
</template>

<script>
    import {api,snackbar, sanitize} from "@/helpers/utils";
    import {mapActions, mapGetters} from 'vuex'
    import ImageUpload from "@/mixins/ImageUpload"
    import kebabCase from 'lodash/kebabCase'

    export default {
        name: "Create",
        mixins: [ImageUpload],
        data:()=>({
            m_page: {
                title: '',
                slug: '',
                excerpt: '',
                body: ''
            },
            process: false
        }),
        computed: {
            ...mapGetters({
               'pages': 'app/pages'
            }),
            isAdd(){
                return this.$route.params.action === 'add'
            },
            page:{
                get:function () {
                    if (!this.isAdd) {
                        let slug = this.$route.params.slug
                        let page = this.pages[slug]
                        if (!page) {
                            this.redirectToList()
                            return this.m_page
                        } else
                            this.m_page = page;
                    }
                    return this.m_page;
                },
                set: function (value) {
                    this.m_page = value
                }
            }
        },
        methods: {
            ...mapActions({
                'updatePage': 'app/updatePage'
            }),
            redirectToList(){
                snackbar('Page slug is invalid!','error')
                this.$router.push({name:'admin.pages.list'})
            },
            post(){
                if(this.page.body.length < 1){
                    snackbar('Please Fill all fields!','error')
                    return
                }

                let invalid_fields = [];

                let test_page = {
                    title: this.page.title,
                    excerpt: this.page.excerpt,
                    slug: this.page.slug
                }
                Object.entries(test_page).forEach(v=>{
                    if (sanitize(v[1])) {
                        invalid_fields.push(v[0])
                    }
                });

                if (invalid_fields.length > 0){
                    let hv = invalid_fields.length > 1 ? 'fields were':'field is';
                    let str = invalid_fields.join(', ');
                    snackbar(`${str} ${hv} invalid. Only text is allowed.`)
                    return;
                }

                this.process = true
                let endpoint = this.isAdd ? 'add' : 'edit';
                api(`page/${endpoint}`,'post',this.page).then(resp=>{
                    snackbar(this.isAdd ? 'Page Added!' : 'Page Edited!','success')
                    this.updatePage(resp.data);
                    this.$router.push({name: 'admin.pages.list'});
                }).catch(error=>{
                    snackbar(error.response.data.error || 'Something went wrong','error')
                }).finally(()=>{
                    this.process = false
                })

            },
            createSlug(){
                if(!this.isAdd)
                    return
                this.page.slug = kebabCase(this.page.title)
            }
        }
    }
</script>