<template>
        <div class="admin-layout">
        <div class="wrapper">
            <aside>
                <h3>Settings</h3>
                <div class="settings">
                    <router-link v-ripple active-class="active" v-for="({name, icon}, link) in settings" :key="link" :to="{name:`admin.${link}`}">
                        <i class="fas" :class="[`fa-${icon}`]"></i>
                        {{name}}
                        </router-link>
                </div>
            </aside>
            <div class="content">
                <transition enter-active-class="animated d-3 fadeIn" leave-active-class="animated d-2 fadeOut" mode="out-in" appear>
                    <router-view />
                </transition>
            </div>
        </div>
        </div>
</template>

<script>
    export default {
        name: "AdminLayout",
        data: ()=>({
            settings: {
                dashboard: {
                    'name': 'Dashboard',
                    'icon': 'chart-line'
                },
                settings: {
                    'name': 'General Settings',
                    'icon': 'cogs'
                },
                proxies: {
                    name: 'Proxies',
                    'icon': 'database'
                },
                code: {
                    'name': 'Codes',
                    'icon': 'code'
                },
                'pages.list': {
                    'name': 'Pages',
                    'icon': 'file'
                },
                menu: {
                    'name': 'Menu',
                    'icon': 'bars'
                },
                account: {
                    'name': 'Account Settings',
                    'icon': 'user-cog'
                }
            }
        })
    }
</script>
