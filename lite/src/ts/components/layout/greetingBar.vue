<template>
    <div class="greeting-bar">
        <div class="greeting">
            Welcome Back <b>{{auth.full_name}}</b>
        </div>
        <div class="time">
            {{time || '00-00-00 AM'}}
        </div>
        <div class="quote">
            {{quote.quote}} <b>{{quote.by}}</b>
        </div>
        <div class="actions">
            <router-link :to="{name: 'admin'}">Dashboard</router-link>
            <button class="style-none" @click="logout">Logout</button>
        </div>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    import moment from 'moment'

    export default {
        name: "greetingBar",
        data:()=>({
            time: ''
        }),
        mounted(){
            setInterval(this.updateTime,1000)
        },
        computed:{
            /**
              * @return {IQuote}
             */
          quote(){
              return this.$store.getters['app/quote']
          },
            /**
              * @return {IUser}
             */
          auth(){
              return this.$store.getters['auth/user']
          }
        },
        methods: {
            ...mapActions({
               'logout': 'auth/logout'
            }),
            updateTime(){
                this.time = moment().format('h:mm:ss A')
            }
        }
    }
</script>