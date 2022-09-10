<template>
        <div class="ad-container" v-if="canShow">

            <template v-if="ad.code === ''">
                <div :class="[`ad-${type}`]" class="add" :style="adStyle">
                    <template v-if="type === 'square'">
                        336x280<br/>
                        {{ad.name | capitalize}} Ad
                    </template>
                    <template v-else-if="type === 'banner'">
                        728x90 {{ad.name | capitalize}} Ad
                    </template>
                    <template v-else>
                        {{ad.name | capitalize}} Ad
                    </template>
                </div>
            </template>

            <template v-else>
                <div :class="[`ad-${type}`]" :style="adStyle" v-html="ad.code"></div>
            </template>

        </div>
</template>

<script>
    import {IAd} from "@/helpers/Interfaces"

    export default {
        name: "adSpace",
        props: {
            type: String,
            adStyle:{
                type: [String, Object, Array],
                required: false,
                default: ''
            },
            dataAdSlot: {
                required: false,
                default: ''
            },
            dataAdFormat: {
                required: false,
                default: ''
            },
            dataFullWidthResponsive: {
                required: false,
                default: ''
            },
            dataAdClient: {
                required: false,
                default: ''
            },
        },
        computed: {
            /**
             * @return IAd
             */
            ad() {
                let ads = this.$store.getters['settings/ads'];
                if (!ads)
                    return null

                let ad = null;
                Object.entries(ads).forEach(v=>{
                    if (v[1].name === this.type)
                        ad = v[1];
                });

                if (typeof ad === "undefined")
                    return null;
                return ad
            },
            canShow() {
                if (!this.ad || typeof this.ad === "undefined")
                    return false
                else if (this.ad.status !== 'enabled')
                    return false
                return true
            }
        },
        mounted() {


            if (typeof window.adsbygoogle !== 'undefined')
                this.pushAd();
            else
                setTimeout(this.invokeAd,1000);
        },
        methods:{
            invokeAd(){
                if (typeof window.adsbygoogle !== 'undefined') {
                    this.pushAd();
                    clearTimeout(this.invokeAd);
                }
            },
            pushAd() {

                if (this.ad) {
                if (this.ad.status === 'disabled')
                    return
               }
               try{
                   (adsbygoogle = window.adsbygoogle || []).push({});
               }catch (e) {
                   console.log('Google Ads Error:\r\n'+ e);
               }
            }
        }
    }
</script>