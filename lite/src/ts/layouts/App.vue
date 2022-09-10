<template>
    <component :is="layout" :layout-class="layoutClass">
    </component>
</template>
<script>
import DefaultLayout from '@/layouts/DefaultLayout'
import EmptyCentered from "@/layouts/EmptyCentered"
import {triggerScript} from "@/helpers/utils"

export default {
    components: {
        'default': DefaultLayout,
        'empty-centered': EmptyCentered
    },
    watch:{
      '$route':{
          immediate: false,
          handler(){
              triggerScript();
          }
      }
    },
    computed : {
        layout(){
        //set layout to simple if layout is not specified
        return this.$route.meta.layout || 'empty-centered'
        },
        layoutClass(){
            return  this.$route.meta.layoutClass || 'default-layout'
        }
   }
}
</script>