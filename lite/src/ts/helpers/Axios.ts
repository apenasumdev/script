// in HTTP.js
import Axios from 'axios'
import NProgress from 'nprogress'


const BASE_URL = document.querySelector('base').getAttribute('href')
const TOKEN = document.querySelector('[name=_token_]').getAttribute('content')

// create a new Axios instance
const HTTP = Axios.create({
    baseURL: BASE_URL,
    responseType: 'json',
    headers: {
        'content-type' : 'multipart/form-data',
        'TOKEN': TOKEN
    }
});

// before a request is made start the NProgress
HTTP.interceptors.request.use(config => {
    NProgress.start();
    return config
});

// before a response is returned stop NProgress
HTTP.interceptors.response.use(response => {
    NProgress.done();
    return response
},error => {
    NProgress.done()
    return Promise.reject(error)
});


export default HTTP