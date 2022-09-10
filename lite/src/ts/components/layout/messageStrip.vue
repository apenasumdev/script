<template>
    <div class="message-strip-container" :class="{'bottom-offset':!error}">
        <div class="message-strip" :class="{'hide':!error}">
            <span class="progress" :style="{'width':progressWidth + '%'}"></span>
            <div class="text">
                {{error}}
            </div>
            <button aria-label="Remove Message" name="remove_message" @click="removeError" class="style-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "messageStrip",
        data:()=>({
            show: true,
            timeout: 5,
            timeoutInterval: null,
            timeoutHandler: null,
            progressWidth: 100,
            lastVal: null
        }),
        watch: {
          'error': {
              immediate: true,
              handler(value){
                  // if (this.waiting)
                  //     return

                  if (value) {
                      if (this.lastVal){
                          if (value !== this.lastVal){
                              this.lastVal = value
                              this.remove()
                              this.start()
                          }else {
                              this.lastVal = value
                              this.start()
                          }
                      }else {
                          this.lastVal = value
                          this.start()
                      }
                  }else {
                      this.remove()
                  }
              }
          }
        },
        computed: {
            error(){
                return this.$store.getters['app/error'];
            }
        },
        methods: {
            removeError(){
                this.$store.dispatch('app/removeError');
            },
            start(){
                this.progressWidth = 100
                this.startTimeout()
                this.setInterval()
            },
            remove(){
                this.progressWidth = 100
                if (this.timeoutInterval)
                    clearInterval(this.timeoutInterval)
                if (this.timeoutHandler)
                    clearTimeout(this.timeoutHandler)
            },
            startTimeout(){
                this.timeoutHandler = setTimeout(()=>{
                    this.removeError();
                    this.progressWidth = 100
                }, this.timeout * 1000);
            },
            setInterval(){
                let time = this.timeout;
                let intervals = 0.1;

                this.timeoutInterval = setInterval(()=>{
                    this.progressWidth = (time * this.progressWidth) / this.timeout;
                    time = time - intervals * 0.3;
                },intervals * 1000);
            }
        }
    }
</script>