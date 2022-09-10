<template>
    <div class="container">
        <div class="hero section-bg-dark">
            <section class="fluid">
                <h1>
                    Download <b>TikTok</b> Videos by Url
                </h1>
                <h2>
                    TikTok video Downloader is a fast and free way to download and save TikTok videos as MP4's.
                    Start by putting the TikTok video URL in the box below and click Go.
                </h2>
                <x-input-search />

                <h5>
                    By using this service you agree to our
                    <router-link :to="{name:'page',params:{slug: 'terms-of-services'}}">terms.</router-link>
                </h5>

                <x-element-ad-space type="below-search" />
            </section>
        </div>
        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
        <router-view />
        </transition>
    </div>
</template>

<script>
    export default {
        name: "HomeLayout"
    }
</script>