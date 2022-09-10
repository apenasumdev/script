<template>
    <div class="snackbar-container">
        <div ref="snackbar" class="snackbar" v-show="snackbar.show">
            <i v-if="['success','error','warning','info'].includes(icon)" class="fas" :class="`fa-${icon}`"></i>
            <i v-else :class="icon"></i>
            <p>{{snackbar.text}}</p>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    export default {
        name: "snackBar",
        data: () => ({
            timeout: 5,
            transitionedIn: false,
            snackbarStyle: {
                display: 'none'
            }
        }),
        watch: {
            'snackbar.show': {
                immediate: true,
                handler(value) {
                    this.snackbarStyle.display = value ? 'flex' : 'none'
                }
            }
        },
        mounted() {
            this.$refs.snackbar.addEventListener('animationend', this.AnimationEnded)
        },
        computed: {
            /**
             *
             * @return {ISnackbar}
             */
            snackbar(){
                return this.$store.getters['app/snackbar']
            },
            icon() {
                if (this.snackbar.type === 'success')
                    return 'check'
                else if (this.snackbar.type === 'error')
                    return 'times'
                else if (this.snackbar.type === 'info' || this.snackbar.type === 'warning')
                    return 'info'
                else return this.snackbar.type
            }
        },
        methods: {
            ...mapActions({
                'closeSnackBar': 'app/closeSnackBar'
            }),
            AnimationEnded() {
                if (!this.transitionedIn)
                    this.transitionedIn = true
                else {
                    this.transitionedIn = false
                    if(this.$refs.snackbar)
                        this.$refs.snackbar.classList.remove('hide')
                    return this.closeSnackBar()
                }
                setTimeout(() => {
                    if(this.$refs.snackbar)
                        this.$refs.snackbar.classList.add('hide')
                }, this.timeout * 1000)
            }
        }
    }
</script>