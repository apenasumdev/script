(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{aQio:function(e,t,n){"use strict";n.r(t);n("QWBl"),n("oVuX"),n("E9XD"),n("pDQq"),n("T63A"),n("07d7"),n("5s+n"),n("mRH6"),n("FZtP"),n("ls82");var s=n("sndj");function a(e,t,n,s,a,i,r){try{var u=e[i](r),o=u.value}catch(e){return void n(e)}u.done?t(o):Promise.resolve(o).then(s,a)}var i={name:"Menu",data:function(){return{m_menus:null,process:!1}},computed:{top:function(){return this.menus.length<1?null:this.menus.reduce((function(e,t){return e.id>t.id?e:t}))},pages:function(){return this.$store.getters["app/pages"]||[]},menus:{get:function(){var e=this.$store.getters["settings/menus"];return e&&(this.m_menus=e),this.m_menus||[]},set:function(e){this.m_menus=e}}},methods:{add:function(){var e=this.top?this.top.id+1:0;this.menus.push({id:e,text:"",link:"",external:!1})},update:function(e){this.m_menus.move(e.moved.oldIndex,e.moved.newIndex)},deleteMenu:function(e){this.menus.splice(e,1)},save:function(){var e=this,t=[];if(Object.entries(this.menus).forEach((function(e){(Object(s.g)(e[1].text)||Object(s.g)(e[1].link))&&t.push(e[1].text)})),t.length>0){var n=t.length>1?"fields were":"field is",i=t.join(", ");Object(s.k)(i+" "+n+" invalid. Only text is allowed.")}else Object(s.a)("update/menu","post",{menu:this.menus},!0).then(function(){var t,n=(t=regeneratorRuntime.mark((function t(n){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return Object(s.k)("Menus updated successfully!","success"),t.next=3,e.$store.dispatch("settings/setMenus",n.data);case 3:case"end":return t.stop()}}),t)})),function(){var e=this,n=arguments;return new Promise((function(s,i){var r=t.apply(e,n);function u(e){a(r,s,i,u,o,"next",e)}function o(e){a(r,s,i,u,o,"throw",e)}u(void 0)}))});return function(e){return n.apply(this,arguments)}}()).catch((function(e){console.error(e.response.data.error||"Something went wrong"),Object(s.k)(e.response.data.error||"Something went wrong","error")}))}}},r=n("KHd+"),u=Object(r.a)(i,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"card setting-card"},[n("form",{on:{submit:function(t){return t.preventDefault(),e.save(t)}}},[n("h3",{staticClass:"with-button"},[e._v("\n        Menu Customization\n        "),n("button",{directives:[{name:"ripple",rawName:"v-ripple"}],staticClass:"style-none add-button"},[e._v("\n            "+e._s(e.process?"Saving":"Save")+"\n            "),n("i",{staticClass:"space fas",class:e.process?"fa-spinner fa-spin":"fa-save"})])]),e._v(" "),n("div",{staticClass:"menu-items"},[n("draggable",{attrs:{draggable:".item"},on:{change:e.update},model:{value:e.menus,callback:function(t){e.menus=t},expression:"menus"}},[e._l(e.menus,(function(t,s){return n("div",{key:t.id,staticClass:"item",attrs:{draggable:"true"}},[n("i",{staticClass:"fas fa-align-justify"}),e._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.text,expression:"menu.text"}],staticClass:"form-input bg",attrs:{"aria-label":"Menu Text",type:"text",placeholder:"Menu Text",required:""},domProps:{value:t.text},on:{input:function(n){n.target.composing||e.$set(t,"text",n.target.value)}}}),e._v(" "),t.external?n("input",{directives:[{name:"model",rawName:"v-model",value:t.link,expression:"menu.link"}],staticClass:"form-input bg",attrs:{"aria-label":"Menu Link",type:"text",placeholder:"Menu Link/ Page Slug",required:""},domProps:{value:t.link},on:{input:function(n){n.target.composing||e.$set(t,"link",n.target.value)}}}):n("select",{directives:[{name:"model",rawName:"v-model",value:t.link,expression:"menu.link"}],staticClass:"form-input bg",attrs:{"aria-label":"Menu Link",name:"link"},on:{change:function(n){var s=Array.prototype.filter.call(n.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(t,"link",n.target.multiple?s:s[0])}}},e._l(e.pages,(function(t,s){return n("option",{key:s,domProps:{value:s}},[e._v("\n                            "+e._s(t.title)+"\n                        ")])})),0),e._v(" "),n("select",{directives:[{name:"model",rawName:"v-model",value:t.external,expression:"menu.external"}],staticClass:"form-input bg",attrs:{"aria-label":"Is External",name:"is-external",id:"is-external-"+t.id},on:{change:function(n){var s=Array.prototype.filter.call(n.target.options,(function(e){return e.selected})).map((function(e){return"_value"in e?e._value:e.value}));e.$set(t,"external",n.target.multiple?s:s[0])}}},[n("option",{domProps:{value:!0}},[e._v("External")]),e._v(" "),n("option",{domProps:{value:!1}},[e._v("Internal")])]),e._v(" "),n("button",{directives:[{name:"ripple",rawName:"v-ripple"}],staticClass:"style-none delete-button py",on:{click:function(t){return t.preventDefault(),e.deleteMenu(s)}}},[n("i",{staticClass:"fas fa-times"})])])})),e._v(" "),e.menus.length<1?n("div",{staticClass:"no-item"},[e._v("\n                Please Add menu from Add button below 👇\n            ")]):e._e(),e._v(" "),n("button",{directives:[{name:"ripple",rawName:"v-ripple"}],staticClass:"style-none add-button mt",attrs:{slot:"footer"},on:{click:function(t){return t.preventDefault(),e.add(t)}},slot:"footer"},[e._v("\n                Add "),n("i",{staticClass:"space fas fa-plus"})])],2)],1)])])}),[],!1,null,null,null);t.default=u.exports}}]);