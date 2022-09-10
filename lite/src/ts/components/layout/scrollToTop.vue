<template>
    <div class="scroll-to-top">
        <button aria-label="Scroll to Top" name="scroll_to_top" class="style-none" :class="{'hide':!visible}" @click="scroll">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
</template>

<script>
    export default {
        name: "scrollToTop",
        data:()=>({
            visibleOffSet: 350,
            visible: false
        }),
        mounted() {
            window.addEventListener('scroll', this.catchScroll)
        },
        destroyed () {
            window.removeEventListener('scroll', this.catchScroll)
        },
        methods: {
            catchScroll(){
                const pastTopOffset = window.pageYOffset > parseInt(this.visibleOffSet)
                const pastBottomOffset = window.innerHeight + window.pageYOffset >= document.body.offsetHeight - parseInt(this.visibleOffSet)
                this.visible = parseInt(this.visibleOffSet) > 0 ? pastTopOffset : pastTopOffset
            },
            scroll(){
                window.smoothScroll()
            }
        }
    }
</script>