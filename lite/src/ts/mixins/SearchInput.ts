import {api, validate,error, searchVideo} from '@/helpers/utils'
import {mapActions} from "vuex"


export default {
    data: ()=>({
        search: '',
        process: false
    }),
    computed: {
        canPaste(){
            return window.navigator.clipboard !== undefined
        }
    },
    methods: {
        ...mapActions({
            'setVideo': 'video/set',
            'setSingle': 'video/setSingleVideo'
        }),
        paste(){
            window.navigator.clipboard.readText().then(text=>{
                this.search = text
            }).catch(e=>{console.error(e)})
        },
        async searchVideo(){
            if (this.search === '')
                return error('Please enter something!')

            return searchVideo(this.search,this.isSingle,
                function(){
                this.process = true;
            }.bind(this), function(){
                this.process = false;
            }.bind(this))

            //DUPLICATED CODE

            // if (!validate(this.search))
            //     return error('Url is invalid! Please check your url pattern.')
            // this.process = true
            //
            // api(`v1/fetch?url=${this.search}`,'get').then(resp=>{
            //
            //     if (!resp.data.user || !resp.data.stats || !resp.data.cover){
            //         if(resp.data.error)
            //             return error(resp.data.error)
            //         else return error('Something went wrong while scraping the video')
            //     }
            //
            //     this.setVideo(resp.data)
            //     this.setSingle(this.isSingle)
            //
            // }).catch(e=>{
            //     error(e.response.data.error || 'Something went wrong');
            // }).finally(()=>{
            //     this.process = false
            // })
        }
    }
}