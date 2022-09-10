export interface IAd {
    status: string | 'enabled'| 'disabled'
    network: string
    code: string
    name?: string
}

export interface IAds {
    [type: string]: IAd
}

export interface IUser {
    id: number
    fname?:string
    lname?:string
    full_name?: string
    email: string
    token: string
    role: string | 'admin' | 'demo'
    admin: boolean
    [key: string]: any
}

export interface IApp {
    name?: string
    desc?: string
    keywords?: string
    version?: string
    api_version?: string
}

export interface IPage {
    id: number
    title: string
    slug: string
    excerpt: string
    body: string
    [key: string]: any
}

export interface IPages {
    [slug: string]: IPage
}

export interface IQuote {
    quote: string
    by: string
}

export interface ISnackbar {
    show: boolean
    type: string | 'success' | 'error' | 'warning' | 'info'|String
    text: string
}

export interface IMenu {
    id: number
    text: string
    link: string
    external: boolean
}

export interface ISocial {
    text?:string
    link?: string
}


export interface Video {
    id: number
    video_id: string|null
    title: string|null
    caption: string|null
    cover: string|null
    dl_count: number
    url: string
    url_nwm?: string|null
    music?: VideoMusic|null
    user?: VideoUser|null
    stats?: VideoStats|null
    uploaded_at?: string
    video_url?: string
    share_url?:string
    [key:string]:any
}
export interface VideoMusic {
    id?: number
    title?: string
    author?: string
    cover?: string
    url?: string
}

export interface VideoUser {
    name: string|null
    username: string|null
    cover: string|null
}

export interface VideoStats {
    shares: number
    likes: number
    comments: number
    play?:number
}