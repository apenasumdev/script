(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"56Wi":function(e,t,a){"use strict";a.r(t);a("QWBl"),a("oVuX"),a("zKZe"),a("T63A"),a("07d7"),a("5s+n"),a("p532"),a("FZtP");var s=a("sndj"),i=a("L2JU"),r={methods:{uploadImage(e,t,a,i){this.process=!0,Object(s.k)("Image Uploading","info"),Object(s.a)("upload/image","post",{image:e}).then(e=>{t.insertEmbed(a,"image",e.data.image),Object(s.k)("Image Uploaded","success"),i()}).catch(e=>{Object(s.k)(e.response.data.error||"Something went wrong!","error")}).finally(()=>{this.process=!1})}}},n=a("N1om"),o=a.n(n),l={name:"Create",mixins:[r],data:function(){return{m_page:{title:"",slug:"",excerpt:"",body:""},process:!1}},computed:Object.assign(Object.assign({},Object(i.c)({pages:"app/pages"})),{},{isAdd:function(){return"add"===this.$route.params.action},page:{get:function(){if(!this.isAdd){var e=this.$route.params.slug,t=this.pages[e];if(!t)return this.redirectToList(),this.m_page;this.m_page=t}return this.m_page},set:function(e){this.m_page=e}}}),methods:Object.assign(Object.assign({},Object(i.b)({updatePage:"app/updatePage"})),{},{redirectToList:function(){Object(s.k)("Page slug is invalid!","error"),this.$router.push({name:"admin.pages.list"})},post:function(){var e=this;if(this.page.body.length<1)Object(s.k)("Please Fill all fields!","error");else{var t=[],a={title:this.page.title,excerpt:this.page.excerpt,slug:this.page.slug};if(Object.entries(a).forEach((function(e){Object(s.g)(e[1])&&t.push(e[0])})),t.length>0){var i=t.length>1?"fields were":"field is",r=t.join(", ");Object(s.k)(r+" "+i+" invalid. Only text is allowed.")}else{this.process=!0;var n=this.isAdd?"add":"edit";Object(s.a)("page/"+n,"post",this.page).then((function(t){Object(s.k)(e.isAdd?"Page Added!":"Page Edited!","success"),e.updatePage(t.data),e.$router.push({name:"admin.pages.list"})})).catch((function(e){Object(s.k)(e.response.data.error||"Something went wrong","error")})).finally((function(){e.process=!1}))}}},createSlug:function(){this.isAdd&&(this.page.slug=o()(this.page.title))}})},p=a("KHd+"),d=Object(p.a)(l,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"card setting-card"},[a("form",{on:{submit:function(t){return t.preventDefault(),e.post(t)}}},[a("h3",{staticClass:"with-button"},[e._v("\n            "+e._s(e.isAdd?"Add Page":"Edit Page")+"\n            "),a("button",{staticClass:"style-none add-button",attrs:{disabled:e.process}},[e.process?[e._v("\n                    "+e._s(e.isAdd?"Publishing":"Saving")+"\n                    "),a("i",{staticClass:"space fas fa-spinner fa-spin"})]:[e._v("\n                    "+e._s(e.isAdd?"Publish":"Save")+"\n                    "),a("i",{staticClass:"space fas fa-save"})]],2)]),e._v(" "),a("div",{staticClass:"form-control"},[a("label",{attrs:{for:"page_title"}},[e._v("Title")]),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.page.title,expression:"page.title"}],attrs:{type:"page_title",id:"page_title",placeholder:"Enter Page Title",required:"",autofocus:""},domProps:{value:e.page.title},on:{input:[function(t){t.target.composing||e.$set(e.page,"title",t.target.value)},e.createSlug]}})]),e._v(" "),a("div",{staticClass:"form-control mt"},[a("label",{attrs:{for:"page_slug"}},[e._v("Slug")]),e._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:e.page.slug,expression:"page.slug"}],attrs:{disabled:!e.isAdd,type:"page_slug",id:"page_slug",placeholder:"Enter Page Slug",required:""},domProps:{value:e.page.slug},on:{input:function(t){t.target.composing||e.$set(e.page,"slug",t.target.value)}}})]),e._v(" "),a("div",{staticClass:"form-control mt"},[a("label",{attrs:{for:"page_excerpt"}},[e._v("Excerpt")]),e._v(" "),a("textarea",{directives:[{name:"model",rawName:"v-model",value:e.page.excerpt,expression:"page.excerpt"}],attrs:{name:"page_excerpt",id:"page_excerpt",required:"",cols:"5",rows:"4"},domProps:{value:e.page.excerpt},on:{input:function(t){t.target.composing||e.$set(e.page,"excerpt",t.target.value)}}})]),e._v(" "),a("div",{staticClass:"form-control mt"},[a("label",{attrs:{for:"page_body"}},[e._v("Body")]),e._v(" "),a("vue-editor",{attrs:{name:"page_body",useCustomImageHandler:"",required:"",id:"page_body"},on:{"image-added":e.uploadImage},model:{value:e.page.body,callback:function(t){e.$set(e.page,"body",t)},expression:"page.body"}})],1)])])}),[],!1,null,null,null);t.default=d.exports}}]);