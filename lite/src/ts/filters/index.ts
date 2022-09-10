import moment from 'moment'
import { split, join, replace } from 'lodash';


export function diffForHuman(value){
    if(!value) return ''

    return moment(value).fromNow()
}

export function capitalize(value){
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
}

export function balance(value){
    if(value > 999 && value < 1000000){
        return (value/1000).toFixed(0) + 'K'; // convert to K for number from > 1000 < 1 million
    }else if(value > 1000000){
        return (value/1000000).toFixed(0) + 'M'; // convert to M for number from > 1 million
    }else if(value < 900){
        return value; // if value < 1000, nothing to do
    }
}

export function liquefied(value:string){
    let _split = split(value,' ');

    _split.forEach((v,index)=>{
        if(/@[\w]+/.test(v)) {
            let regex = v.replace(/[^\w@]+/g,'');
            let anchor =  `<a href="https://www.tiktok.com/${regex}" target="_blank">${regex}</a>`
            _split[index] = v.replace(regex,anchor);
        }
        else if(/#[\w]+/.test(v)) {
            let regex = v.replace(/[^\w#]+/g,'');
            let tag = `<a href="https://www.tiktok.com/tag/${replace(regex, '#', '')}" target="_blank">${regex}</a>`
            _split[index] = v.replace(regex,tag)
        }
    });
    return join(_split,' ');
}

export default {
    install: function (Vue) {
        Vue.filter('diffForHuman',diffForHuman)
        Vue.filter('capitalize',capitalize)
        Vue.filter('balance',balance)
        Vue.filter('liquefied',liquefied)
    }
}