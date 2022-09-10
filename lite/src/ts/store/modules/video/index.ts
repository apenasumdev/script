import Vue from 'vue'
import {Video} from "@/helpers/Interfaces"

const state = {
    video: null,
    loading: false,
    singleVideo: true,
    recent: [],
    trending: []
}

const getters = {
     video: (state) => state.video,
     loading: (state) => state.loading,
     singleVideo: (state) => state.singleVideo,
     recent: (state) => state.recent,
     trending: (state) => state.trending
}

const actions = {
    remove(state){
        state.commit('remove')
    },
    set(state, payload:Video|null) {
        state.commit('set', payload)
    },
    setLoading(state, payload:boolean){
        state.commit('setLoading', payload)
    },
    setSingleVideo(state,payload:boolean){
        state.commit('setSingleVideo', payload)
    },
    setRecent(state,payload:Video[]|Video){
        state.commit('setRecent', payload)
    },
    setTrending(state,payload:Video[]|Video){
        state.commit('setTrending', payload)
    }
}

const mutations = {
    remove(state) {
        Vue.set(state,'video',null)
    },
    set(state, payload:Video|null){
        Vue.set(state,'video',payload)
    },
    setLoading(state, payload:boolean) {
        Vue.set(state,'loading',payload)
    },
    setSingleVideo(state, payload: boolean) {
        Vue.set(state,'singleVideo',payload)
    },
    setRecent(state, payload:Video|Video[]|null ) {
        if (Array.isArray(payload))
        {   // @ts-ignore
            Vue.set(state.recent, payload.video_id, payload);
        } else Vue.set(state,'recent',payload)
    },
    setTrending(state, payload:Video|Video[]|null ) {
        if (Array.isArray(payload))
        {   // @ts-ignore
            Vue.set(state.trending, payload.video_id, payload);
        } else Vue.set(state,'trending',payload)
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
