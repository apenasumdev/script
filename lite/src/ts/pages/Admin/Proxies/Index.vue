<template>
    <div class="card setting-card">
            <h3 class="with-button">
                Proxies
               
            </h3>
        <template v-if="!fetching">
        <form @submit.prevent="addProxy">
             <div class="inputGroupProxy">
                 <div class="form-control">
                     <input
                             ARIA-LABEL="Add Proxy"
                             type="text"
                             id="proxy"
                             v-model="proxy"
                             :disabled="process"
                             placeholder="Enter Proxy"
                             required
                             autofocus
                     >
                     <p class="input_tooltip">
                         Proxy should match following pattern: http://ip:port OR http://username:password@ip:port
                     </p>
                 </div>
                 <button
                         v-ripple
                         class="style-none add-button"
                         :disabled="process"
                 >
                     {{process ? 'Checking': 'Add'}}
                     <i
                             class="space fas"
                             :class="process ? 'fa-spinner fa-spin':'fa-plus'"
                     ></i>
                 </button>
             </div>
        </form>

        <div v-if="!proxies.length" class="no-item">
            {{ can_view  ? 'No proxies found.' : 'You are not allowed to view this.' }}
        </div>
        
        <div v-if="proxies.length" class="proxiesList">

            <div
                    class="inputGroupProxy"
                    v-for="(proxi,index) in proxies"
                    :key="index"
            >
                <div class="form-control">
                    <input
                            ARIA-LABEL="Add Proxy"
                            type="text"
                            :value="proxi"
                            readonly
                    >
                </div>
                <button
                        @click.prevent="deleteProxy(index)"
                        v-ripple
                        :disabled="process || deletingIndex === index"
                        class="style-none delete-button py"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>
        
        </div>
        </template>
        <div v-else class="fetching-div">
            Loading Proxies
        </div>
       
        
    </div>
</template>

<script>
import { api, snackbar, error } from "@/helpers/utils"
import axios from "@/helpers/axios"

export default {
    name: "Index",
    data: ()=>({
        process: false,
        proxy: '',
        proxies: [],
        fetching: false,
        deletingIndex: null,
        can_view: true,
    }),
    watch: {
    	auth: {
    		immediate: true,
            handler(value){
    			if(typeof value !== 'undefined' && value !== null){
    				if(typeof value.email !== 'undefined')
						this.onLogin()
                }
            }
        }
    },
    computed: {
		auth(){
			return this.$store.getters['auth/user']
		}
    },
	methods: {
    	async onLogin(){
			if(!this.auth.admin){
				this.can_view = false
				return
			}
			await this.getProxies()
        },
    	async getProxies(){
    		this.fetching = true
            try {
				let req = await api ( 'proxies', 'get' )
				let proxies = req.data
                
                if(Array.isArray(proxies))
                	this.proxies = proxies
                
			}catch ( e ){
				this.handleError(e)
            }
            
            this.fetching = false
        },
    	async addProxy(){
            
            if(this.proxies.indexOf(this.proxy) !== -1)
            	return snackbar('This proxy is already in list.','error')
			
            
            if(!this.validate(this.proxy))
            	return error('The proxy is invalid.')
            
            this.process = true
            
            try {
				let res = await api ( 'proxies', 'post', {
					proxy: this.proxy
				} )
	
                let proxies = res.data
                
                if(Array.isArray(proxies))
                    this.proxies = proxies
    
			}catch ( e ){
            	this.handleError(e)
            }
            
            this.process = false
        },
        async deleteProxy(index){
	
    		let proxy = this.proxies[index] || null
            
            if(typeof proxy === 'undefined' || proxy === null)
            	return error('Proxy not found. Try again.')
    		
            this.deletingIndex = index
            
			try {
				let res = await axios.delete('/api/proxies',{
					params: {proxy}
                })
		
				let proxies = res.data
		
				if(Array.isArray(proxies))
					this.proxies = proxies
		
			}catch ( e ){
				this.handleError(e)
			}
	
			this.deletingIndex = null
        },
        validate(value){
            return /^http:\/\/[^\n]+:[^\n]+$/.test(value)
            || /^http:\/\/[^\n]+:[^\n]+@\/[^\n]+:[^\n]+$/.test(value)
        },
        handleError(e){
			let _error = typeof e === 'string' ? e : e.response?.data?.error ?? e.message
			error(_error ?? 'Something went wrong.')
        }
    }
}
</script>
