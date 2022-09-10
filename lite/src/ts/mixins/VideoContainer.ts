import PerfectScrollbar from "perfect-scrollbar"
import {searchVideo} from "@/helpers/utils"
import {Video} from "@/helpers/Interfaces"

export default {
    data: () => ({
        ps: null,
        loading: false
    }),
    watch: {
        'records': {
            immediate: true,
            handler(value) {
                if (value.length < 1)
                    this.loadVideos()
                if (this.ps)
                    this.ps.update()
            }
        }
    },
    computed: {
        records(): Video[]|[] {
            return this.$store.getters[this.getter];
        }
    },
    mounted() {
        this.ps = new PerfectScrollbar(this.$refs[this.ref_item], {
            wheelSpeed: 2,
            wheelPropagation: true,
            suppressScrollX: true
        })
    },
    destroyed() {
        if (this.ps) {
            this.ps.destroy()
            this.ps = null
        }
    },
    methods: {
        /**
         *
         * @param {Video} video
         */
        async openVideo(video) {
            await searchVideo(video.video_url, false)
        }
    }
}