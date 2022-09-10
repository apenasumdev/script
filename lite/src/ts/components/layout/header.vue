<template>
    <header>
        <nav>
            <div class="logo">
                <button aria-label="Open Drawer" class="style-none" @click="openDrawer">
                    <i class="fas fa-bars"></i>
                    </button>
                <img @click="goToHome" src="/assets/images/logos/logo-wide.png" class="can-hover pointer" :alt="app.name">
            </div>
            <div class="mobile-logo">
                <img @click="goToHome" class="can-hover pointer" src="/assets/images/logos/logo.png" :alt="app.name">
            </div>
            <transition enter-active-class="animated d-2 zoomIn" leave-active-class="animated d-2 zoomOut" appear mode="out-in">
                <div class="nav" v-if="isLanding">
                    <a v-if="support" class="nav-support" :href="support.link || null">
                        <span>Support</span> <i class="fas fa-envelope-open-text"></i>
                    </a>
                </div>
                <div class="search" v-if="!isLanding">
                    <button aria-label="Open Search Input" class="style-none transition" @click="searchFocus = !searchFocus">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </transition>
        </nav>
        <x-input-search-header v-if="!isLanding" v-model="searchFocus" />
    </header>
</template>
<script>
export default {
    props: ['value'],
    data:()=>({
        searchFocus: false,
        showDrawer: false
    }),
    watch:{
      'value':{
          immediate: true,
          handler(value){
              this.showDrawer = value
          }
      }
    },
    computed: {
        /**
         *
         * @return {IApp}
         */
        app(){
          return this.$store.getters['app/info']
        },
        /**
         *
         * @return {boolean}
         */
        isLanding(){
            return this.$route.fullPath === '/'
        },
        /**
         *
         * @return {ISocial|null}
         */
        support(){
            let socials = this.$store.getters['settings/socials']
            if(!socials)
                return null
            let mail = null
            Object.values(socials).forEach(s=>{
                if (s.text === 'mail')
                    mail = s
            })
            return mail
        }
    },
    methods: {
        goToHome(){
            this.$router.push({name: 'home'})
        },
        openDrawer(){
            this.showDrawer = true
            this.$emit('input',this.showDrawer)
        }
    }
}
</script>