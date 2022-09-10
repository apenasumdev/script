<template>
    <div class="card login-card">
        <form @submit.prevent="login">
            <div class="form-control">
                <label for="email">Admin Email</label>
                <input type="email" id="email" v-model="user.email" placeholder="Enter Email" required autofocus autocomplete>
            </div>
            <div class="form-control mt">
                <label for="password">Password</label>
                <input type="password" id="password" v-model="user.password" placeholder="Password" required autocomplete>
            </div>
            <button :disabled="!valid || process" class="submit-button mt">
                {{process ? 'Authenticating': 'Login'}}
            </button>
        </form>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    export default {
        name: "Login",
        data: ()=>({
            user: {
                email: '',
                password: ''
            },
        }),
        computed: {
            /**
             *
             * @return {boolean}
             */
            process(){
                return this.$store.getters['auth/process']
            },
            valid(){
                return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
                        .test(this.user.email)
                        && this.user.password.length > 2
            }
        },
        methods: {
            ...mapActions({
                'loginUser': 'auth/loginUser'
            }),
            login(){
                if(!this.valid)
                    return
                this.loginUser(this.user)
            }
        }
    }
</script>