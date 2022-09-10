<template>
    <div class="card setting-card">
        <form @submit.prevent="save">
            <h3 class="with-button">
                Extra Code
                <button type="submit" :disabled="process" class="style-none add-button">
                    {{process ? 'Saving': 'Save'}}
                    <i class="space fas" :class="process ? 'fa-spinner fa-spin':'fa-save'"></i>
                </button>
            </h3>
            <div class="form-control">
                <label for="extra_code">This code will go to &lt;head&gt; tag</label>
                <textarea name="extra_code" id="extra_code" v-model="code.code" cols="5" rows="5"></textarea>
            </div>
            <h3 class="mt with-button">Ads Code
                <button @click="addNew" v-ripple class="style-none add-button">
                    <i class="fas fa-plus"></i>
                </button>
            </h3>
            <transition-group enter-active-class="animated d-3 fadeIn" leave-active-class="animated d-2 fadeOut">
            <div class="form-control mb" v-for="(ad, index) in ads" :key="index">
                <div class="network-grid">
                    <input type="text" name="type" v-model="ad.name" required placeholder="Ad Name|Identifier" :disabled="[0,1].includes(index)">
                    <select v-model="ad.network">
                        <option v-for="(network,index) in networks" :key="index" :value="network">{{network | capitalize}}</option>
                    </select>
                    <select v-model="ad.status">
                        <option value="enabled">Enabled</option>
                        <option value="disabled">Disabled</option>
                    </select>
                    <button :disabled="[0,1].includes(index)" @click.prevent="deleteAd(index)" class="style-none delete-button py">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <textarea :name="index" :id="index" v-model="ad.code" cols="5" rows="5"></textarea>
            </div>
            </transition-group>
        </form>
    </div>
</template>

<script>
    import {api, snackbar, sanitizeComment} from "@/helpers/utils"

    export default {
        name: "Codes",
        data: ()=>({
            networks: ['adsense'],
            m_ads: null,
            m_code: {
                code: ''
            },
            process: false
        }),
        computed: {
            /**
             * @return {IAd[]}
             */
            ads:{
                get:function(){
                    let ads = this.$store.getters['settings/ads'];
                    if (ads)
                        this.m_ads = ads
                    if (this.auth) {
                        if(!this.auth.admin) {
                            if (ads !== undefined) {
                                Object.entries(this.m_ads).forEach(v=>{
                                    this.$set(this.m_ads,v[0].code,'');
                                })
                            }
                        }
                    }
                    return this.m_ads || this.$store.getters['settings/ads'] || {}
                },
                set:function(value){
                    this.m_ads = value
                }
            },
            /**
             * @return {IUser}
             */
            auth(){
              return this.$store.getters['auth/user']
            },
            /**
             * @return {string}
             */
            code:{
                get:function(){
                    let code = this.$store.getters['settings/code']
                    if(code)
                        this.m_code.code = code
                    if (this.auth) {
                        if (!this.auth.admin)
                            this.m_code.code = ''
                    }
                    return this.m_code || this.$store.getters['settings/code']
                },
                set:function(value){
                    this.m_code = value
                }
            }
        },
        methods: {
            save(){

                if (!this.auth)
                    return
                if (!this.auth.admin)
                    return snackbar('Permission Denied!','error')

                let invalid_fields = [];

                if (sanitizeComment(this.code.code))
                    invalid_fields.push('code')

                Object.entries(this.ads).forEach(v=>{
                    if (sanitizeComment(v[1].code)) {
                        invalid_fields.push(v[0])
                    }
                })

                if (invalid_fields.length > 0){
                    let hv = invalid_fields.length > 1 ? 'fields were':'field is';
                    let str = invalid_fields.join(', ');
                    snackbar(`${str} ${hv} invalid. Comments are not allowed.`)
                    return;
                }

                api('update/code','post',{code: btoa(this.code.code), ads: this.ads},true).then(resp=>{
                    snackbar('Codes updated successfully!','success')
                    this.$store.dispatch('settings/setAds',resp.data.ads)
                    this.$store.dispatch('settings/setCode',resp.data.code)
                }).catch(e=>{
                    snackbar(e.response.data.error || 'Something went wrong','error')
                    console.error(e.response.data.error || 'Something went wrong')
                })
            },
            deleteAd(index){
                this.ads.splice(index, 1);
            },
            addNew(){
                this.ads.push({
                   name: '',
                   code: '',
                   network: this.networks[0],
                   status: 'enabled'
                });
            }
        }
    }
</script>
