import cookies from 'js-cookie'
//@ts-ignore
import router from '@/routes'
//@ts-ignore
import store from '@/store'

export const auth = (to, from, next)=>{
    if(cookies.get('access_token') !== undefined)
        return next()
    router.push({name: 'home'})
}
export const guest = (to, from, next)=>{
    if(cookies.get('access_token') !== undefined)
        router.push({name: 'admin.dashboard'})
    else next()
}
export const logout = async (to, from, next)=>{
    await store.dispatch('auth/logout')
    router.push({name: 'home'})
}