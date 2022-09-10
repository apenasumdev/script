<template>
    <div  class="search" :class="{'focus':focus}">
        <input aria-label="Search Video" type="text" :readonly="process" v-model="search" @keydown.enter="searchVideo()" class="style-none search-input" placeholder="Enter video url">
        <i class="fas fa-search search-icon"></i>
        <div class="actions">
            <button aria-label="Paste Clipboard Text" @click.prevent="paste" class="style-none copy-icon" v-if="canPaste && !process">
                <i class="fas fa-copy"></i>
            </button>
            <div v-if="process" class="spinner-container border-left">
                <span class="spinner"></span>
            </div>
            <button aria-label="Search Video" v-else class="style-none close-icon border-left" @click="close()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>
<script>
import SearchInput from '@/mixins/SearchInput'
export default {
    mixins: [SearchInput],
    props: ['value'],
    data: ()=>({
        focus: false,
        isSingle: false
    }),
    watch: {
        'value':{
            immediate: true,
            handler(value){
                this.focus = value
            }
        }
    },
    methods: {
        close(){
            if(this.process)
                return
            this.focus = false
            this.$emit('input',this.focus)
        }
    }
}
</script>