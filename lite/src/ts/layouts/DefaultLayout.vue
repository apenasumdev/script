<template>
    <div class="application">
        <x-layout-greeting-bar v-if="user !== null && (!canLock || drawerState)" />
        <main class="default-layout"  :class="[canLock ? 'scroll-lock': '', layoutClass]">
            <x-layout-header v-model="drawerState" />
            <x-layout-drawer v-model="drawerState" />
            <transition enter-active-class="animated d-3 fadeIn" leave-active-class="animated d-3 FadeOut" mode="out-in">
                <router-view :key="$route.meta.group || $route.path"></router-view>
            </transition>
            <x-element-ad-space v-if="!hideAds" type="banner" class="space-below" />

            <x-layout-footer />
        </main>
        <x-layout-video-box />
        <x-element-snack-bar />
        <x-layout-message-strip />
        <x-layout-scroll-to-top />
        <x-layout-loader />
    </div>
</template>
<script>
import {mapActions} from 'vuex'
export default {

    props: {
        layoutClass: {
            type: String,
            default: 'default-layout'
        }
    },
    watch: {
        'drawerState':{
            immediate: true,
            handler(value){
                this.changeLock(value)
            }
        },
      'locked':{
        handler(value){
          if (value){
            this.scrollTop = window.scrollY
            this.canLock = true
          }
          else{
              this.canLock = false
              setTimeout(() => {
                if(this.scrollTop)
                  window.scrollBy(0, this.scrollTop)
                this.scrollTop = null
              }, 500)

          }
        }
      }
    },
    data:()=>({
        drawerState: false,
        scrollTop: null,
        m_canLock: false
    }),
    computed: {
        /**
         *
         * @return {boolean}
         */
        locked(){
            return this.$store.getters['app/locked']
        },
        /**
         *
         * @return {IUser}
         */
        user(){
            return this.$store.getters['auth/user']
        },
        hideAds(){
            if(this.$route.name === 'Page404') return true
            if (!this.$route.meta.group)
                return false
            return this.$route.meta.group === 'admin';
        },
        canLock:{
            get: function () {
            return this.m_canLock
            },
            set: function (value) {
            this.m_canLock = value
            }
        }
        
    },
    methods: {
        ...mapActions({
            'changeLock': 'app/changeLock'
        })
    }
}
</script>