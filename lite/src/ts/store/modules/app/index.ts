import Vue from 'vue'
import quotes from "@/_DUMMY_META_/quotes"
import sample from 'lodash/sample'
import {api} from "@/helpers/utils"
import {IAds, IApp, IPages, IQuote} from '@/helpers/Interfaces'

const state = {
    info: {} as IApp,
    snackbar: {
        show: false,
        type: 'success',
        text: 'Processed Successful!'
    },
    locked: true as boolean,
    ads: null as IAds,
    quote: null as IQuote,
    pages: {} as IPages,
    error: null
}
const getters = {
    info: (state)=> state.info,
    snackbar:(state)=> state.snackbar,
    locked: (state)=> state.locked,
    pages:(state)=> state.pages,
    ads: (state) => state.ads,
    error: (state)=> state.error,
    quote(state){
        if(!state.quote)
            Vue.set(state,'quote',sample(quotes))
        return state.quote
    }
}
const actions = {
    loadInfo(state,payload){
        state.commit('loadInfo',payload)
    },
    showSnackBar(state,payload){
        state.commit('showSnackBar',payload)
    },
    closeSnackBar(state){
        state.commit('closeSnackBar')
    },
    changeLock(state,payload){
        state.commit('changeLock',payload)
    },
    loadPages(state,payload){
        state.commit('loadPages',payload)
    },
    updatePage(state, payload) {
        state.commit('updatePage',payload)
    },
    overridePages(state, payload){
        state.commit('overridePages',payload)
    },
    setError(state, payload){
        state.commit('setError',payload)
    },
    removeError(state){
        state.commit('removeError')
    }
}
const mutations = {
    loadInfo(state, payload) {
        Object.entries(payload).forEach(([key, value])=>{
            Vue.set(state.info,key,value)
        })
    },
    showSnackBar(state,payload){
        Vue.set(state,'snackbar',payload)
        Vue.set(state.snackbar,'show',true)
    },
    closeSnackBar(state){
        Vue.set(state.snackbar,'show',false)
    },
    changeLock(state, payload) {
        Vue.set(state,'locked',payload)
    },
    async loadPages(state, payload) {
        return await api('page/load').then(resp=>{
           Vue.set(state,'pages',resp.data)
        }).catch(e=>{
            console.error(e.response.data.error)
        });
    },
    async overridePages(state,payload){
        Vue.set(state,'pages',{})
        Object.entries(payload).forEach(v=>{
            Vue.set(state.pages,v[0],v[1])
        })
    },
    updatePage(state,payload){
        Object.values(payload).forEach(v=>{
            Vue.set(state.pages,v['slug'],v)
        })
        //Vue.set(state.pages,payload.slug,payload)
    },
    setError(state, payload) {
        Vue.set(state,'error',payload)
    },
    removeError(state) {
        Vue.set(state,'error',null)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}