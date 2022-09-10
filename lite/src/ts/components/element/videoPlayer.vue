<template>
    <div class="video-player" :style="{backgroundImage: `url(${value.cover})`}">
        <div class="blur" ></div>
        <video :src="value.url" ref="video_player" controls class="video" :poster="value.cover || ''" loop playsinline></video>
        <div class="play-control" @click="play" :class="{'show':paused}">
            <i class="fas" :class="[paused ? 'fa-play' : 'fa-pause']"></i>
        </div>
        <div class="sound-control" @click="mute">
            <i class="fas" :class="[muted ? 'fa-volume-mute' : 'fa-volume-up' ]"></i>
        </div>
    </div>
</template>

<script>
    /**
     * @var {Video} value
     */
    export default {
        name: "videoPlayer",
        props: ['value'],
        data: ()=>({
            paused: true,
            muted: false
        }),
        methods: {
            /**
             *
             * @return {HTMLVideoElement|null}
             */
            player(){
              return this.$refs.video_player || null
            },
            play(){
                if(!this.player())
                    return
                this.player().paused ? this.player().play() : this.player().pause()
                this.paused = this.player().paused
            },
            mute(){
                if(!this.player())
                    return
                this.player().volume = this.player().volume === 0 ? 1 : 0
                this.muted = this.player().volume === 0
            }
        }
    }
</script>