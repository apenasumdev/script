<template>
    <div class="top-search">
        <div class="header">
            Top Searches
        </div>
        <div ref="items_list_container" class="body">
            <div v-if="Object.keys(records).length > 0" class="items">
                <div class="items">
                    <div v-ripple class="item" v-for="(item, index) in records" :key="index" @click="openVideo(item)">
                        <img :src="item.cover" :alt="item.title">
                        <div class="details">
                            <img :src="item.user.cover" :alt="item.user.name">
                            <p><i class="fas fa-play"></i> {{item.stats.play | balance}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else-if="loading" class="spinner-container max-size center">
                <span class="spinner"></span>
            </div>
            <div v-else class="no-records">
                There are no records!
            </div>
        </div>
    </div>
</template>

<script>
    /**
     * @var {Video[]} records
     */
    import {api, snackbar} from "@/helpers/utils"
    import VideoContainer from "@/mixins/VideoContainer"
    export default {
        name: "topSearch",
        mixins: [VideoContainer],
        data: () => ({
            getter: 'video/trending',
            ref_item: 'items_list_container'
        }),
        methods: {
            loadVideos(){
                this.loading = true

                api('trending', 'get')
                    .then((resp) => {
                        let data = Object.keys(resp.data).length > 0 ? resp.data : {};
                        this.$store.dispatch('video/setTrending', data);

                    }).catch(e => {
                    snackbar(e.response.data.error || 'Something went wrong', 'error');
                }).finally(()=>{
                    this.loading = false
                });
            }
        }
    }
</script>