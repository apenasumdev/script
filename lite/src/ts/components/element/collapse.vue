<template>
        <div class="collapse">
            <div class="header" @click="updateFocus()" v-ripple>
                <h2><slot name="title"/></h2>
                <i class="fas" 
                :class="`fa-${show ? 'minus': 'plus'}`">
                </i>
            </div>
                <x-transition-collapse>
                    <div class="body" v-show="show">
                        <p><slot name="body" /></p>
                    </div>
                </x-transition-collapse>
        </div>
</template>
<script>
export default {
    props: ['value','index'],
    data:()=>({
        focus: 0
    }),
    watch:{
        'value':{
            immediate: true,
            handler(value){
                this.focus = value
            }
        }
    },
    computed: {
        show(){
            return this.focus === this.index
        }
    },
    methods: {
        updateFocus(){
            if(this.focus === this.index){
                this.close()
                return
            }
            this.focus = this.index
            this.$emit('input',this.focus)
        },
        close(){
            this.focus = -1
            this.$emit('input',this.focus)
        }
    }
}
</script>