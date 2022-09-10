<template>
    <div class="card setting-card">
        <form @submit.prevent="save">
            <h3 class="with-button">
                General Settings
                <button v-ripple :disabled="process" class="style-none add-button">
                    {{process ? 'Saving': 'Save'}}
                    <i class="space fas" :class="process ? 'fa-spinner fa-spin':'fa-save'"></i>
                </button>
            </h3>
            <div class="form-control">
                <label for="api_ver">API Version</label>
                <select name="api_ver" v-model="app.api_version" id="api_ver">
                    <option value="v1" disabled>Version 1 (NOT WORKING)</option>
                    <option value="v15" disabled>Version 1.5 (NOT WORKING)</option>
                    <option value="wrapper">Wrapper API</option>
                    <option disabled value="v2">Version 2 (Coming Soon)</option>
                </select>
            </div>
            <div class="form-control mt">
                <label for="site_name">Site Name</label>
                <input type="site_name" id="site_name" v-model="app.name" placeholder="Enter Site Name" required autofocus>
            </div>
            <div class="form-control mt">
                <label for="site_desc">Site Description</label>
                <textarea name="site_desc" id="site_desc" v-model="app.desc" required cols="5" rows="5"></textarea>
            </div>
            <div class="form-control mt">
                <label for="site_keywords">Site Keywords</label>
                <textarea name="site_keywords" id="site_keywords" v-model="app.keywords" required cols="5" rows="2"></textarea>
            </div>

            <h3 class="mt with-button">Social Links
                <button :disabled="Object.keys(socials).length > 6" class="style-none add-button" @click.prevent="addLink"><i class="fas fa-plus"></i></button>
            </h3>


            <div class="social-links">
                <transition-group enter-active-class="animated fadeIn d-3" leave-active-class="animated fadeOut d-3" >
                <div v-for="(link, key) in socials" :key="key" class="social-link-grid">
                    <div class="form-control">
                        <select :name="link.text" v-model="link.text" :id="link.text">
                            <option v-for="brand in brands" :disabled="isSelected(brand)" :value="brand">{{brand | capitalize}}</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <input aria-label="Link Url" v-model="link.link" type="text" name="link_url"required placeholder="Enter Link Url">
                    </div>
                    <button @click.prevent="deleteLink(index)" class="style-none delete-button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                </transition-group>
                <div class="no-social-links" v-if="Object.keys(socials).length < 1">
                    No Social Links
                </div>
            </div>




        </form>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {api,snackbar, sanitize} from "@/helpers/utils"
    export default {
        name: "Settings",
        data:()=>({
            m_app: null,
            m_socials: null,
            brands: ['facebook','youtube','twitter','whatsapp','snapchat','mail','instagram'],
            process: false
        }),

        computed: {
            /**
             * @return {IApp}
             */
            app:{
                get: function () {
                    let app = this.$store.getters['app/info']
                    if(app)
                        this.m_app = app
                    return this.m_app || this.$store.getters['app/info']
                },
                set: function (value) {
                    this.m_app = value
                }
            },
            /**
             * @return {ISocial[]}
             */
            socials: {
                get:function () {
                    let socials = this.$store.getters['settings/socials']
                    if(socials)
                        this.m_socials = socials;
                    return this.m_socials || []
                },
                set: function (value) {
                    this.m_socials = value
                }
            }
        },
        methods: {
            deleteLink(index){
                this.socials.splice(index,1)
            },
            addLink(){
                this.socials.push({
                    text: '',
                    link: ''
                })
            },
            isSelected(text){
                let selected = false
                this.socials.forEach((v)=>{
                    if(v.text === text){
                        selected = true
                        return
                    }
                })
                return selected
            },
            save(){
                let invalid_fields = [];
                Object.entries(this.app).forEach(v=>{
                   if (sanitize(v[1])) {
                       invalid_fields.push(v[0])
                   }
                });

                Object.entries(this.socials).forEach(v=>{
                    if (sanitize(v[1].link)) {
                        invalid_fields.push(v[1].text)
                    }
                });

                if (invalid_fields.length > 0){
                    let hv = invalid_fields.length > 1 ? 'fields were':'field is';
                    let str = invalid_fields.join(', ');
                    snackbar(`${str} ${hv} invalid. Only text is allowed.`)
                    return;
                }

                api('update/setting','post',{app: this.app, socials: this.socials},true)
                    .then(async resp=>{
                        await this.$store.dispatch('settings/setSocials',resp.data.socials)
                        await this.$store.dispatch('app/loadInfo',resp.data.app)
                        snackbar('Settings Updated','success')
                    }).catch(e=>{
                        console.error(e.response.data.error || 'Something went wrong')
                        snackbar(e.response.data.error || 'Something went wrong','error')
                })
            }
        }
    }
</script>