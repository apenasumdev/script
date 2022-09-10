import Axios from "@/helpers/Axios"
import axios from "axios"
import store from "@/store"

/**
 *
 * @param {string} api
 * @param {string} method
 * @param {object} payload
 * @param {boolean} stringify
 */
export async function api(api:string,method:string='post',payload?:object, stringify?:boolean){
    return await Axios[method](`/api/${api}`,formData(payload,stringify))
}

/**
 * Load Json From Script
 * @param {string} tag
 */
export function loadJsonFromScript(tag:string){
    let script = document.querySelector(`#${tag}`)
    let json = JSON.parse(script.innerHTML)[0]
    script.remove()
    return json
}

/**
 *
 * @param {object} collection
 * @param {boolean} stringify
 * @return FormData
 */
export function formData(collection:object, stringify?:boolean){
    if (!collection)
        return new FormData()
    let formData = new FormData()
    Object.entries(collection).forEach((param)=>{
        formData.append(param[0],typeof param[1] === "object" && stringify ? JSON.stringify(param[1]): <any>param[1])
    })
    return formData
}

/**
 * Detect any html tag
 * @param text
 * @return boolean
 */
export function sanitize(text){
    return /((\<\/?[^]+\>)+)/g.test(text);
}

/**
 * Detect any html comment
 * @param text
 * @return boolean
 */
export function sanitizeComment(text){
    return /((\<\![^]+\>)+)/g.test(text);
}

/**
 * Check if url is a valid http/https url
 * @param url
 */
export function validateURL(url){
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

/**
 * Validating if the url matches the given url pattern
 * @param url
 */
export function validate(url){
    return  /^(https?:\/\/)?(www\.)?vm\.tiktok\.com\/[^\n]+\/?$/.test(url)
        || /^(https?:\/\/)?(www\.)?m\.tiktok\.com\/v\/[^\n]+\.html([^\n]+)?$/.test(url)
        || /^(https?:\/\/)?(www\.)?tiktok\.com\/@[^\n]+\/video\/[^\n]+$/.test(url)
        || /^(https?:\/\/)?(www\.)?vt\.tiktok\.com\/[^\n]+\/?$/.test(url)
}

/**
 * Display an error
 * @param error
 */
export async function error(error){
    await store.dispatch('app/setError',error)
}

/**
 * Display a snackbar
 * @param text
 * @param type
 */
export async function snackbar(text,type?:'success'|'error'|'warning'|'info'|String){
    await store.dispatch('app/showSnackBar',{type: type, text: text})
}


export function makeId(length: number = 16): string {
    let text = '';
    const char_list = '0123456789';
    for (let i = 0; i < length; i += 1) {
        text += char_list.charAt(Math.floor(Math.random() * char_list.length));
    }
    return text;
}

export async function searchVideo(url:string,singleVideo:boolean=true, beforeSend?: Function, onResponse?: Function){
    if (!validate(url))
        return error('URL is invalid!')
    await store.dispatch('video/setLoading',true)

    if(beforeSend) beforeSend()

    //ADD HTTPS TO URL
    if(url.indexOf('https://') === -1 && url.indexOf('http://')) url = `https://${url}`;


    //FAILED IMPLEMENTATION
    // try {
    //
    //     let tt_webid_v2 = `68${makeId(16)}`
    //
    //     let ttReq = await axios.get(url)
    //     let body = ttReq.data
    //     if(!body)
    //         throw new Error('Your IP is blocked by TikTok. Try to use VPN.')
    //
    //     let dom = new DOMParser().parseFromString(body,"text/html")
    //     let next_data:any = dom.querySelector('#__NEXT_DATA__')?.innerHTML
    //
    //     if(!next_data)
    //         throw new Error('Your IP is blocked by TikTok. Try to use VPN.')
    //
    //     next_data = JSON.parse(next_data)
    //
    //     let pageProps = next_data?.props?.pageProps?.itemInfo
    //
    //
    //     if(!pageProps)
    //         throw new Error('Something went wrong. Cannot scrape video!')
    //
    //     pageProps.itemStruct = {
    //         id: pageProps?.itemStruct?.id,
    //         desc: pageProps?.itemStruct?.desc,
    //         createTime: pageProps?.itemStruct?.createTime,
    //         video: pageProps?.itemStruct?.video,
    //         author: pageProps?.itemStruct?.author,
    //         music: pageProps?.itemStruct?.music,
    //         stats: pageProps?.itemStruct?.stats
    //     }
    //
    //     let resp = await Axios.get('/api/v1/fetch-by-json',{
    //         params: {
    //             json: pageProps,
    //             tt_webid_v2,
    //             userAgent: navigator.userAgent
    //         },
    //     })
    //
    //     if (!resp.data.user || !resp.data.stats || !resp.data.cover){
    //         if(resp.data.error)
    //             return error(resp.data.error)
    //         else return error('Something went wrong while scraping the video')
    //     }
    //     await store.dispatch('video/setSingleVideo',singleVideo)
    //     await store.dispatch('video/set',resp.data)
    //
    // }catch (e){
    //     let _error = typeof e === 'string' ? e : e.response?.data?.error ?? e.message
    //
    //     await error(_error || 'Something went wrong');
    // }
    //
    // if(onResponse) onResponse()
    // await store.dispatch('video/setLoading',false)
    //

    try {
        let resp = await api(`v1/fetch?url=${url}`,'get')
        if (!resp.data.user || !resp.data.stats || !resp.data.cover){
            if(resp.data.error)
                throw new Error(resp.data.error)
            else throw new Error('Something went wrong while scraping the video')
        }

        await store.dispatch('video/setSingleVideo',singleVideo)
        await store.dispatch('video/set',resp.data)
    }catch (e){
        let _error = typeof e === 'string' ? e : e.response?.data?.error ?? e.message

        await error(_error || 'Something went wrong');
    }

    if(onResponse) onResponse()
    await store.dispatch('video/setLoading',false)

    // api(`v1/fetch?url=${url}`,'get').then(async resp=>{
    //
    //     if (!resp.data.user || !resp.data.stats || !resp.data.cover){
    //         if(resp.data.error)
    //             return error(resp.data.error)
    //         else return error('Something went wrong while scraping the video')
    //     }
    //     await store.dispatch('video/setSingleVideo',singleVideo)
    //      await store.dispatch('video/set',resp.data)
    // }).catch(e=>{
    //     error(e.response.data.error || 'Something went wrong');
    // }).finally(async ()=>{
    //     if(onResponse) onResponse()
    //     await store.dispatch('video/setLoading',false)
    // })
}

let originalTitle = null
let originalURL = null

export function setTitle(title = null,restoring = false) {
    let app = store.getters['app/info']
    if(!restoring)
        originalTitle = document.title
    else {
        document.title = title
        return
    }
    if (!app.name)
        return
    if(!title)
        document.title = app.name
    else document.title = `${title} - ${app.name}`
}
export function restoreTitle(){
    if (originalTitle)
        setTitle(originalTitle,true)
    else setTitle()
}

export function changeURL(video_id,restoring = false){
    let base_url = location.origin
    let url = `${base_url}/video/${video_id}`
    if (!restoring) {
        originalURL = location.href
        triggerScript()
    }
    else {
        url = originalURL
        originalURL = null
    }

    if (!restoring)
        window.history.pushState(null,'',url)
    else window.history.replaceState(null,'',url)

    if (!restoring){
        window.onpopstate = async function(){
            await store.dispatch('video/remove')
        }
    } else {
        window.onpopstate = null
    }
}

export function restoreURL() {
    if (originalURL){
        changeURL(null,true)
    }
}

export function triggerScript(){
    let code = document.querySelector('#ANALYTICS_CODE_TD')
    if (code)
        eval(code.innerHTML)
}