<template>
    <transition enter-active-class="animated d-3 fadeIn" leave-active-class="animated d-3 FadeOut" mode="out-in" appear>
        <div class="video-box" v-if="video">
            <div class="wrapper" @keydown.esc="close" autofocus tabindex="15">
                <div class="video-player-container">
                    <x-element-video-player v-model="video" />
                    <button @click="close" aria-label="Close Video Box" class="style-none close-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <aside>
                    <div class="user-profile">
                        <div class="avatar">
                            <img :src="video.user.cover" :alt="video.user.name">
                        </div>
                        <div class="details">
                            <h3>{{video.user.username}}</h3>
                            <h4>{{video.user.name}} <span>.</span> {{video.uploaded_at | diffForHuman}}</h4>
                        </div>
                        <a aria-label="More Info" v-ripple class="style-none more-link" target="_blank" :href="`https://www.tiktok.com/@${video.user.username}`">
                            More <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                    <div class="text" v-if="video.caption" :inner-html.prop="video.caption | liquefied">
                    </div>
                    <div class="music" v-if="video.music">
                        <i class="fas fa-music"></i>
                        <b>{{video.music.title}} - {{video.music.author}}</b>
                        <a aria-label="Download Music" v-if="video.music.url" v-ripple :href="`/download?id=${video.video_id}&type=music`" target="_blank">
                            <i class="fas fa-download"></i>
                            Music
                        </a>
                    </div>
                    <div class="states">
                        <div class="states-count" v-if="video.stats">
                            <div class="likes" v-if="video.stats.likes">
                                <i class="far fa-heart"></i>
                                {{video.stats.likes | balance}}
                            </div>
                            <div class="comments" v-if="video.stats.comments">
                                <i class="far fa-comment-dots"></i>
                                {{video.stats.comments | balance}}
                            </div>
                            <div class="shares" v-if="video.stats.shares">
                                <i class="far fa-eye"></i>
                                {{video.stats.play | balance}}
                            </div>
                        </div>
                        <div class="share-actions">
                            <template v-if="shareAPI">
                                <button @click="share" aria-label="Share Video Link" class="style-none share-action share-link" v-ripple>
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </template>
                            <template v-else>
                                <a aria-label="Share on Facebook" class="share-action facebook" v-ripple :href="`https://www.facebook.com/sharer.php?u=${video.share_url}`" target="_blank">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a aria-label="Share on Twitter" class="share-action twitter" v-ripple :href="`https://twitter.com/intent/tweet?url=${video.share_url}`" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a aria-label="Share on Whatsapp" class="share-action whatsapp" v-ripple :href="`whatsapp://send?text=${video.share_url}`" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </template>
                            <button @click="shareShow = !shareShow" aria-label="Copy Video Link" class="style-none share-action copy-link" v-ripple>
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                    <transition enter-active-class="animated fadeIn d-3" leave-active-class="animated fadeOut d-3" appear>
                        <div class="share-url" v-if="shareShow">
                            <input ref="copy_input" class="video-url-input" aria-label="Video URL" type="text" readonly :value="video.share_url">
                            <i class="fas fa-link prepend"></i>

                            <button @click="copy" class="style-none">
                                <i class="fas" :class="`fa-${copyIcon}`"></i>
                            </button>
                        </div>
                    </transition>

                    <x-element-ad-space type="square" />
                    <transition enter-active-class="animated d-3 fadeIn" leave-active-class="animated d-3 FadeOut" mode="out-in">
                    <button :disabled="fetching" @click="fetch" aria-label="Fetch Download Links" v-ripple v-if="!fetched" class="style-none button fetch-button mt-auto" :class="{'fetching':fetching}">
                        <i class="fas fa-fan"></i>
                        <span>Fetch Download Links</span>
                    </button>
                    <div v-else class="button-group">
                        <a :disabled="!available.original" aria-label="Download Original Video" target="_blank" v-ripple :href="`/download?id=${video.video_id}&type=video&nwm=false`" class="button accent-button">
                            <i class="fas fa-download"></i>
                            Original
                        </a>
                        <a :disabled="!available.nwm" aria-label="Download No Watermark Video" target="_blank" v-ripple :href="`/download?id=${video.video_id}&type=video&nwm=true`" class="button primary-button download-btn-nwm">
                            <i class="fas fa-download"></i>
                            No Watermark
                        </a>
                    </div>
                    </transition>
                </aside>
            </div>
        </div>
    </transition>
</template>
<script>
    import {setTitle, restoreTitle, api, error, snackbar, changeURL, restoreURL} from "@/helpers/utils"
    import {mapActions} from 'vuex'
    export default {
        name: "videoBox",
        data:()=>({
            fetched: false,
            fetching: false,
            available: {
                original: true,
                nwm: true
            },
            shareShow: false,
            copyIcon: 'copy'
        }),
        watch: {
          video: {
              immediate: true,
              handler(value){
                  if (value) {
                      this.changeLock(true)
                      if (!this.singleVideo) {
                          setTitle(value.title)
                          changeURL(value.video_id)
                      }
                  }
                  else {
                      this.changeLock(false)
                      this.fetched = false
                      if (!this.singleVideo){
                          restoreTitle();
                          restoreURL();
                      }
                  }
              }
          },
        },
        computed: {
            /**
             *
              * @return {Video|null}
             */
          video(){
              return this.$store.getters['video/video'];
          },
            /**
              * @return {boolean}
             */
          singleVideo(){
              return this.$store.getters['video/singleVideo']
          },
          shareAPI(){
              return navigator.share
          }
        },
        methods: {
            ...mapActions({
                'changeLock':'app/changeLock',
                'removeVideo': 'video/remove'
            }),
            fetch(){
                this.fetching = true

                api(`v1/fetch-videos/${this.video.video_id}`,'post').then(resp=>{

                    if(resp.data.nwm !== undefined) {
                        this.available = resp.data;
                        this.fetching = false
                        this.fetched = true
                    }else{
                        error('Something went wrong');
                        this.fetching = false
                    }

                }).catch(e=>{
                    this.fetching = false
                    error(e.response.data.error || 'Something went wrong');
                })
            },
            close(){
                this.removeVideo()
                if (this.singleVideo && this.$route.name !== 'home')
                    this.$router.push({name: 'home'})
            },
            share(){
                if (navigator.share){
                    navigator.share({
                        title: this.video.title,
                        url: this.video.share_url
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    })
                }
            },
            copy(){
                if (!this.$refs['copy_input'])
                    return;
                this.$refs['copy_input'].select();
                document.execCommand('copy');
                this.copyIcon = 'clipboard-check';
                snackbar('URL is copied to clipboard.','fas fa-clipboard-check')
                setTimeout(()=>{
                    this.copyIcon = 'copy';
                },2000);
            }
        }
    }
</script>