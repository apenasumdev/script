require('@/helpers/customPrototypes')

import Vue from "vue"
import App from '@/layouts/App.vue'
import router from "@/routes"
import store from "@/store"
//Cookie for storing user token
import cookies from 'js-cookie'
//Vue Ripple
import Ripple from 'vue-ripple-directive'
//Components
import components from '@/components'
import filters from '@/filters'
import {loadJsonFromScript, searchVideo, error} from "@/helpers/utils";
import VueEditor from "vue2-editor";
import draggable from 'vuedraggable'


Vue.use(VueEditor)
Vue.component('draggable',draggable)
Vue.use(components)
Vue.use(filters)
//Ripple
Ripple.color = 'var(--ripple-effect)'
Vue.directive('ripple', Ripple)

new Vue({
    store,
    router,
    beforeMount,
    beforeCreate,
    render: h => h(App)
}).$mount("#app");

//Before Mounting
async function beforeMount(){
    //Check Auth
    let token = cookies.get('access_token')
    if(token)
        await store.dispatch('auth/loginWithToken',{'token':token})
}
//Before Creating
async function beforeCreate(){
    //Loading Data
    await store.dispatch('app/loadInfo',loadJsonFromScript('__APP__'))
    let props = loadJsonFromScript('__PROPS__');
    //Error Handling
    if (props.error) {
        error(props.error);
    }

    let pages = loadJsonFromScript('__PAGES__');
    if (pages)
        await store.dispatch('app/updatePage',pages);

    if (props.menus)
        await store.dispatch('settings/setMenus',props.menus);

    if (props.codes){
        if (props.codes.ads)
            await store.dispatch('settings/setAds',props.codes.ads)
        if (props.codes.code)
            await store.dispatch('settings/setCode',props.codes.code)
    }

    if (props.socials)
        await store.dispatch('settings/setSocials',props.socials)

    //Load Video
    if (props.video){
        await searchVideo(props.video.video_url);
    }
}

