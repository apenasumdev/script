import Vue from 'vue'
import cookies from 'js-cookie'
import router from '@/routes'
import store from '@/store'
import {api} from '@/helpers/utils'
import User from '@/helpers/User'
import {IUser} from "@/helpers/Interfaces"

const state = {
    user: null as IUser,
    process: false
}
const getters = {
    user(state){
        return state.user ? new User(state.user) : null
    },
    process: (state)=> state.process
}
const actions = {
    loginUser(state,payload){
        state.commit('loginUser',payload)
    },
    loginWithToken(state,payload){
        state.commit('loginWithToken',payload)
    },
    updateUser(state,payload){
        state.commit('updateUser',payload)
    },
    logout(state){
        state.commit('logout')
    }
}
const mutations = {
    async loginUser(state,payload){
        //Set Process to True
        Vue.set(state,'process',true)
        //Send Request
        api('login','post',payload).then(r=>{
            //Get User
            Vue.set(state,'user',r.data.user)
            //Set Access Token
            cookies.set('access_token',r.data.user.token)
            //Dispatch Snackbar
            store.dispatch('app/showSnackBar',{
                show: true,
                type: 'success',
                text: 'LoggedIn! Redirecting in a moment'
            })
            //Redirect in 2 sec
            setTimeout(()=>{
                router.push({name: 'admin.dashboard'})
            },2000)
            //Catch Error
        }).catch(e=>{
            //Get Msg
            let error = e.response.data.error
            //Check Error
            switch(error){
                case 'invalid_credentials':
                    error = "Invalid Credentials!"
                    break;
                case 'invalid_password':
                    error = "Password is invalid!"
                    break;
                default:
                    error = "Something went wrong!"
            }
            //Display Snackbar
            store.dispatch('app/showSnackBar',{
                show: true,
                type: 'error',
                text: error
            })

            //Remove Process
        }).finally(()=>{
            if(state.user === null)
                Vue.set(state,'process',false)
        })
    },
    async loginWithToken(state,payload){
        //Send request
        api('login','post',payload).then(resp=>{
            //Set User
            Vue.set(state,'user',resp.data.user)
            //Set Token
            cookies.set('access_token',resp.data.user.token)
        }).catch(async r=>{
            //Logout if user is empty
            if(!state.user)
                await store.dispatch('auth/logout')
        })
    },
    updateUser(state, payload) {
        if (payload.email)
            Vue.set(state,'user',payload)
    },
    async logout(state){
        api('logout').then(resp=>{
            Vue.set(state,'user',null)
            cookies.remove('access_token')
            router.push({name: 'home'})
        }).catch(e=>{console.error(e)})
    },
    closeSnackBar(state){
        Vue.set(state.snackbar,'show',false)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}