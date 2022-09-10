import Vue from "vue"
import {IAds, IMenu, ISocial} from "@/helpers/Interfaces"

const state = {
    menus: null as IMenu[],
    ads: null as IAds,
    code: null,
    socials: null as ISocial[]
}
const getters = {
    menus : (state) => state.menus,
    ads : (state) => state.ads,
    code : (state) => state.code,
    socials: (state) => state.socials
}
const actions = {
    setMenus(state, payload){
        state.commit('setMenus',payload)
    },
    setAds(state, payload){
        state.commit('setAds',payload)
    },
    setCode(state, payload){
        state.commit('setCode',payload)
    },
    setSocials(state, payload){
        state.commit('setSocials', payload)
    }
}
const mutations = {
    setMenus(state, payload:IMenu[]) {
        Vue.set(state,'menus',payload)
    },
    setAds(state, payload:IAds) {
        Vue.set(state,'ads',payload)
    },
    setCode(state, payload:string) {
        Vue.set(state,'code',payload)
    },
    setSocials(state, payload:ISocial[]) {
        Vue.set(state,'socials',payload)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}