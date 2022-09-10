<template>
    <div class="card setting-card">
        <form @submit.prevent="submit">
            <h3 class="with-button">
                Admin Account
                <button type="submit" v-ripple :disabled="process" class="style-none add-button">
                    {{process ? 'Saving': 'Save'}}
                    <i class="space fas" :class="process ? 'fa-spinner fa-spin':'fa-save'"></i>
                </button>
            </h3>
            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" id="email" v-model="user.email" placeholder="Admin Email" required autocomplete="email" autofocus>
            </div>
            <div class="form-control mt">
                <label for="password">Password</label>
                <input type="password" id="password" v-model="user.password" placeholder="Admin Password" autocomplete="password">
            </div>
        </form>
    </div>
</template>

<script>

    import {mapActions} from "vuex"
    import {api, snackbar, sanitize} from "@/helpers/utils"

    export default {
        name: "Account",
        data: ()=>({
            m_user: null,
            process: false
        }),

        computed: {
            /**
             * @return {IUser}
             */
            user:{
                get:function(){
                    let user = this.$store.getters['auth/user']
                    if(user)
                        this.m_user = user.getAttributes()
                    return this.m_user || this.$store.getters['auth/user'] || {email:'',password:''}
                },
                set:function(value){
                    this.m_user = value
                }
            },
            auth(){
                return this.$store.getters['auth/user']
            }
        },
        methods: {
            ...mapActions({
               'updateUser': 'auth/updateUser'
            }),
            submit(){
                if (!this.auth)
                    return
                if (!this.auth.admin){
                    snackbar('Permission Denied!','error')
                    return
                }

                let invalid_fields = [];
                Object.entries(this.user).forEach(v=>{
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
                console.log(this.user)
                api('user/edit','post',this.user).then(resp=>{
                    snackbar('User Updated','success')
                    this.updateUser(resp.data.user)
                }).catch(error=>{
                    snackbar(error.response.data.error || 'Something went wrong','error')
                }).finally(()=>{
                    this.process = false
                })
            }
        }
    }
</script>