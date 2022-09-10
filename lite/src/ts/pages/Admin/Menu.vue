<template>
    <div class="card setting-card">
        <form @submit.prevent="save">
        <h3 class="with-button">
            Menu Customization
            <button v-ripple class="style-none add-button">
                {{process ? 'Saving': 'Save'}}
                <i class="space fas" :class="process ? 'fa-spinner fa-spin':'fa-save'"></i>
            </button>
        </h3>
        <div class="menu-items">
            <draggable @change="update" v-model="menus" draggable=".item">
                    <div class="item" v-for="(menu, index) in menus" :key="menu.id" draggable="true">
                        <i class="fas fa-align-justify"></i>
                        <input aria-label="Menu Text" type="text" class="form-input bg" v-model="menu.text" placeholder="Menu Text" required>
                        <input aria-label="Menu Link" v-if="menu.external" type="text" class="form-input bg" v-model="menu.link" placeholder="Menu Link/ Page Slug" required>
                        <select class="form-input bg" aria-label="Menu Link" name="link" v-model="menu.link" v-else>
                            <option v-for="(page, key) in pages" :value="key" :key="key">
                                {{page.title}}
                            </option>
                        </select>
                        <select aria-label="Is External" class="form-input bg" v-model="menu.external" name="is-external" :id="`is-external-${menu.id}`">
                            <option :value="true">External</option>
                            <option :value="false">Internal</option>
                        </select>
                        <button @click.prevent="deleteMenu(index)" v-ripple class="style-none delete-button py">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <div v-if="menus.length < 1" class="no-item">
                    Please Add menu from Add button below 👇
                </div>
                <button class="style-none add-button mt" v-ripple @click.prevent="add" slot="footer">
                    Add <i class="space fas fa-plus"></i>
                </button>

            </draggable>
        </div>
        </form>
    </div>
</template>

<script>
    import {api, snackbar, sanitize} from "@/helpers/utils"

    export default {
        name: "Menu",
        data: ()=>({
            m_menus: null,
            process: false,
        }),
        computed: {
            top(){
                if (this.menus.length < 1)
                    return null
                return this.menus.reduce((prev, current) => (prev.id > current.id) ? prev : current)
            },
            /**
             * @return {IPages|[]}
             */
            pages(){
                return this.$store.getters['app/pages'] || [];
            },
            /**
             * @return {IMenu[]}
             */
            menus:{
                get:function () {
                    let menus = this.$store.getters['settings/menus']
                    if(menus)
                        this.m_menus = menus
                    return this.m_menus || [];
                },
                set: function (value) {
                    this.m_menus = value
                }
            }
        },
        methods: {
            add(){
                let newId = this.top ? this.top.id + 1: 0;
                this.menus.push({
                    id: newId,
                    text: '',
                    link: '',
                    external: false
                })
            },
            update(m){
                this.m_menus.move(m.moved.oldIndex,m.moved.newIndex)
            },
            deleteMenu(index){
                this.menus.splice(index, 1)
            },
            save(){

                let invalid_fields = [];
                Object.entries(this.menus).forEach(v=>{
                    if (sanitize(v[1].text) || sanitize(v[1].link)) {
                        invalid_fields.push(v[1].text)
                    }
                });

                if (invalid_fields.length > 0){
                    let hv = invalid_fields.length > 1 ? 'fields were':'field is';
                    let str = invalid_fields.join(', ');
                    snackbar(`${str} ${hv} invalid. Only text is allowed.`)
                    return;
                }

                api('update/menu','post',{menu: this.menus},true)
                .then(async resp=>{
                    snackbar('Menus updated successfully!','success')
                    await this.$store.dispatch('settings/setMenus',resp.data)
                }).catch(e=>{
                    console.error(e.response.data.error || 'Something went wrong')
                    snackbar(e.response.data.error || 'Something went wrong','error')
                })
            }
        }
    }
</script>