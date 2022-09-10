import Vue from "vue";
import Vuex from "vuex";

//MODULES
import app from './modules/app'
import auth from './modules/auth'
import video from './modules/video'
import settings from './modules/settings'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        app,
        auth,
        video,
        settings
    }
})