<template>
    <div class="recent-search">
        <div class="header">
            Recent Searches
        </div>
        <div ref="top_item_list_container" class="body">
            <div v-if="Object.keys(records).length > 0" class="items">
                <div v-for="(item, index) in records" :key="index" v-ripple class="item">
                    <img :src="item.user.cover" :alt="item.user.name">
                    <p>{{item.caption}}</p>
                    <a @click.prevent="openVideo(item)"><i class="fas fa-external-link-alt"></i></a>
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

    import {api, snackbar} from "@/helpers/utils"
    import VideoContainer from "@/mixins/VideoContainer"
    export default {
        name: "recentSearch",
        mixins: [VideoContainer],
        data: () => ({
            getter: 'video/recent',
            ref_item: 'top_item_list_container'
        }),
        methods: {
            loadVideos(){
                this.loading = true
                api('recent', 'get')
                    .then((resp) => {
                        let data = Object.keys(resp.data).length > 0 ? resp.data : {};
                        this.$store.dispatch('video/setRecent', data);

                    }).catch(e => {
                    snackbar(e.response.data.error || 'Something went wrong', 'error');
                }).finally(()=>{
                    this.loading = false
                });
            }
        }
    }
</script>