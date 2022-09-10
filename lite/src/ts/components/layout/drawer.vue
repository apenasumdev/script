<template>
    <div ref="drawer" class="drawer" :class="{'show':open}" :style="drawerStyle">
        <span class="overlay" @click="close"></span>
        <aside>
            <button aria-label="Close Drawer" class="style-none close-btn" @click="close">
               <span></span>
               <span></span>
            </button>
            <div class="content">
                <div class="logo">
                    <img src="/assets/images/logos/logo.png" :alt="app.name">
                </div>
                <h3 class="title">Pages</h3>
                <div class="pages">
                    <template v-for="(menu, index) in menus">
                        <router-link v-ripple v-if="!menu.external" :key="index" :data-label="menu.text" active-class="active" :to="{name:'page',params:{slug:menu.link}}">
                            {{menu.text}}
                        </router-link>
                        <a :href="menu.link" v-else :key="index" target="_blank">{{menu.text}}</a>
                    </template>
                </div>
            </div>
            <div class="version" v-if="app.version">
                <p>Version {{app.version}}</p>
            </div>
        </aside>
    </div>
</template>

<script>
    import {IApp} from "@/helpers/Interfaces"

    export default {
        props: ['value'],
        name: "drawer",
        data:()=>({
            open: false,
            drawerStyle: {
                display: 'none'
            }
        }),
        watch: {
            'value':{
                immediate: true,
                handler(value){
                    this.open = value
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
             * @return {IMenu[]}
             */
            menus(){
                return this.$store.getters['settings/menus']
            }
        },
        mounted() {
            //Animation End Event
            this.$refs.drawer.querySelector('aside')
                .addEventListener('animationend',this.changeStyle)
        },
        destroyed() {
            //Remove Event
            if (this.$refs.drawer)
            this.$refs.drawer.querySelector('aside')
                .removeEventListener('animationend',this.changeStyle)
        },
        methods: {
            changeStyle(){
                this.drawerStyle.display = this.open ? 'block': 'none'
            },
            close(){
                this.drawerStyle.display = 'block'
                this.open = false
                this.$emit('input',this.open)
            }
        }
    }
</script>
