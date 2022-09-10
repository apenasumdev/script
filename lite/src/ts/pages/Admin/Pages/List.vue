<template>
    <div class="card setting-card without-x-padding">
        <h3 class="with-button">
            Pages
            <router-link v-ripple :to="{name: 'admin.pages.modify',params: {action: 'add'}}" class="style-none add-button">
                Add New <i class="space fas fa-plus"></i>
            </router-link>
        </h3>
        <table>
            <col style="width:5%">
            <col style="width:50%">
            <col style="width:15%">
            <col style="width:15%">
            <col style="width:20%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(page, index) in pages">
                    <td>{{page.id}}</td>
                    <td class="start">{{page.title}}</td>
                    <td>{{page.created_at | diffForHuman}}</td>
                    <td>{{page.updated_at | diffForHuman}}</td>
                    <td class="end">
                        <router-link v-ripple :to="{name: 'admin.pages.modify',params: {action: 'edit',slug: page.slug}}" class="btn style-none edit-button">
                            <i class="fas fa-edit"></i>
                        </router-link>
                        <button v-ripple class="btn style-none delete-button py" @click="deletePage(page.id)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {mapActions} from 'vuex'
    import {api} from "@/helpers/utils"
    export default {
        name: "List",
        computed: {
            /**
             * @return {IPages}
             */
            pages(){
                return this.$store.getters['app/pages']
            }
        },
        mounted() {
        },
        methods: {
            ...mapActions({
                'loadPages': 'app/loadPages',
                'overridePages': 'app/overridePages',
                'showSnackBar': 'app/showSnackBar'
            }),
            deletePage(id){
                if (!confirm('Are you sure?!'))
                    return
                api('page/delete','post',{id: id})
                    .then(resp=>{
                        this.showSnackBar({
                            type: 'success',
                            text: 'Page deleted!'
                        })
                        this.overridePages(resp.data)
                    }).catch(e=>{
                        this.showSnackBar({
                            type: 'error',
                            text: e.response.data.error
                        })
                })
            }
        }
    }
</script>