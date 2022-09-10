<template>
    <div class="search">
        <input aria-label="Search Video" type="text" @keydown.enter="searchVideo()" :readonly="process" v-model="search" class="style-none search-input" placeholder="Enter video url">
        <i class="fas fa-search search-icon"></i>
        <div class="actions">
            <button aria-label="Paste Clipboard Text" @click.prevent="paste" class="style-none copy-icon" v-if="canPaste && !process">
                <i class="fas fa-copy"></i>
            </button>
            <div v-if="process" class="spinner-container border-left">
                <span class="spinner"></span>
            </div>
            <button aria-label="Search Video" v-else class="style-none search-text border-left" @click="searchVideo()">
                Search
            </button>
        </div>
    </div>
</template>
<script>
import SearchInput from '@/mixins/SearchInput'
export default {
    mixins: [SearchInput],
    data:()=>({
        isSingle: true
    })
}
</script>