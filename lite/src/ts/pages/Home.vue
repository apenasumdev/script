<template>
    <div class="sections">
        <div class="section section-bg">
            <section class="fluid">

                <h1>
                    How to Save TikTok Videos
                    <span class="divider">
                        <svg width="96" height="7" viewBox="0 0 96 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20.5" y="0.329163" width="55" height="6" rx="3"/>
                            <rect x="10.748" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="89.004" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="0.995972" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="79.252" y="0.329163" width="6" height="6" rx="3"/>
                        </svg>
                    </span>
                </h1>

                <x-element-ad-space type="above-steps" class="mt-4" />

                <div class="steps">
                    <div v-ripple class="step" v-for="(step, index) in steps" :key="index">
                        <div class="circle-icon">
                            <i class="fas" :class="`fa-${step.icon}`"></i>
                        </div>
                        <h3>{{`${index + 1}. ${step.title}`}}</h3>
                        <h4>{{step.caption}}</h4>
                    </div>
                </div>

                <x-element-ad-space type="below-steps" class="mt-4" />

            </section>
        </div>
        <div class="section section-bg less-padding-bottom" v-if="how_to_download_page">
            <section class="fluid">
                <h1>
                    {{how_to_download_page.title}}
                    <span class="divider">
                        <svg width="96" height="7" viewBox="0 0 96 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20.5" y="0.329163" width="55" height="6" rx="3"/>
                            <rect x="10.748" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="89.004" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="0.995972" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="79.252" y="0.329163" width="6" height="6" rx="3"/>
                        </svg>
                    </span>
                </h1>

                <x-element-ad-space type="above-how-to-download" class="mt-4" />


                <div class="post-content max-overflow" v-html="how_to_download_page.body">
                </div>
                <router-link :to="{name: 'page',params: {slug: how_to_download_page.slug}}" v-ripple class="style-none read-more">
                    Read More <i class="fas fa-angle-right"></i>
                </router-link>

                <x-element-ad-space type="below-how-to-download" />

            </section>
        </div>
        <div class="section section-bg">
            <section class="fluid">


                <h1>
                     Frequently Asked Questions
                    <span class="divider">
                        <svg width="96" height="7" viewBox="0 0 96 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20.5" y="0.329163" width="55" height="6" rx="3"/>
                            <rect x="10.748" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="89.004" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="0.995972" y="0.329163" width="6" height="6" rx="3"/>
                            <rect x="79.252" y="0.329163" width="6" height="6" rx="3"/>
                        </svg>
                    </span>
                </h1>

                <x-element-ad-space type="above-faq" class="mt-4" />


                <div class="faqs">
                    <x-element-collapse v-for="(faq, index) in faqs" v-model="current_collapse" :index="index" :key="index" >
                        <template v-slot:title>
                            {{faq.title}}
                        </template>
                        <template v-slot:body>
                        {{faq.body}}
                        </template>
                    </x-element-collapse>
                </div>
            </section>
        </div>
    </div>
</template>
<script>
    import {setTitle} from "@/helpers/utils"
export default {
    data: ()=>({
        steps: [
            {
                'title': 'Find the Video',
                'caption': 'Cick Share of your video and at the Share options, find Copy Link button',
                'icon': 'video'
            },
            {
                'title': 'Paste URL',
                'caption': 'Paste the TikTok link into the input field above and hit the "Search" button.',
                'icon': 'paste'
            },
            {
                'title': 'Download File',
                'caption': 'Wait for our server to do its job and then, save the video to your device.',
                'icon': 'download'
            }
        ],
        current_collapse: 0,
        faqs: [
            {
                'title': 'Can i download tiktok videos on iPhone / iPad / iPod ?',
                'body': 'Yes, this site is specially designed for iOS devices .. all you need to do is to open this site from Safari and Paste video URL and start download'
            },
            {
                'title': 'Does this service needs any money after some Downloads limit ?',
                'body': 'No, this service is Totally Free and has no limit to download.'
            },
            {
                'title': 'Where are TikTok videos saved after being downloaded ?',
                'body': 'Videos usually saved under "Downloads" folder but you may used Save As Option and changed it to another one you can make a simple Check on Windows by Pressing [ CTRL+J ] and if you are using MAC you can Press [ Shift+Command+J ] in your Browser to view your download history.'
            },
            {
                'title': 'Why the video is playing instead of downloading ?',
                'body': 'You Can solve this issue, instead of left clicking use the Right Click -> Save as...and choose the location you would like to save the video to.'
            },
            {
                'title': 'Does this website keep a copy of videos or downloaded videos or search history ?',
                'body': 'This servce is offcially not associated with TikTok. It does not host or entertain any pirated or copyright content on its server and all the videos that are downloaded are done directly from their CDN servers on to the respective user machine.'
            }
        ]
    }),
    computed: {
        /**
         * @return {IPage|null}
         */
        how_to_download_page(){
            let pages = this.$store.getters['app/pages'];
            let page = pages['how-to-download'];
            if (!page) return null
            return page
        },
        /**
         * @return {IApp|null}
         */
        app(){
            return this.$store.getters['app/info'] || null;
        }
    },
    mounted() {
        if (this.$route.name === 'home' && this.app !== null)
            setTitle()
    }
}
</script>