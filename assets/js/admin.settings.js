(window.webpackJsonp=window.webpackJsonp||[]).push([[9],{"5SrR":function(t,e,r){"use strict";r.r(e);r("QWBl"),r("oVuX"),r("pDQq"),r("T63A"),r("07d7"),r("5s+n"),r("mRH6"),r("FZtP"),r("ls82");var n=r("sndj");function o(t,e,r,n,o,i,a){try{var s=t[i](a),c=s.value}catch(t){return void r(t)}s.done?e(c):Promise.resolve(c).then(n,o)}var i={name:"Settings",data:function(){return{m_app:null,m_socials:null,brands:["facebook","youtube","twitter","whatsapp","snapchat","mail","instagram"],process:!1}},computed:{app:{get:function(){var t=this.$store.getters["app/info"];return t&&(this.m_app=t),this.m_app||this.$store.getters["app/info"]},set:function(t){this.m_app=t}},socials:{get:function(){var t=this.$store.getters["settings/socials"];return t&&(this.m_socials=t),this.m_socials||[]},set:function(t){this.m_socials=t}}},methods:{deleteLink:function(t){this.socials.splice(t,1)},addLink:function(){this.socials.push({text:"",link:""})},isSelected:function(t){var e=!1;return this.socials.forEach((function(r){r.text!==t||(e=!0)})),e},save:function(){var t=this,e=[];if(Object.entries(this.app).forEach((function(t){Object(n.g)(t[1])&&e.push(t[0])})),Object.entries(this.socials).forEach((function(t){Object(n.g)(t[1].link)&&e.push(t[1].text)})),e.length>0){var r=e.length>1?"fields were":"field is",i=e.join(", ");Object(n.k)(i+" "+r+" invalid. Only text is allowed.")}else Object(n.a)("update/setting","post",{app:this.app,socials:this.socials},!0).then(function(){var e,r=(e=regeneratorRuntime.mark((function e(r){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.$store.dispatch("settings/setSocials",r.data.socials);case 2:return e.next=4,t.$store.dispatch("app/loadInfo",r.data.app);case 4:Object(n.k)("Settings Updated","success");case 5:case"end":return e.stop()}}),e)})),function(){var t=this,r=arguments;return new Promise((function(n,i){var a=e.apply(t,r);function s(t){o(a,n,i,s,c,"next",t)}function c(t){o(a,n,i,s,c,"throw",t)}s(void 0)}))});return function(t){return r.apply(this,arguments)}}()).catch((function(t){console.error(t.response.data.error||"Something went wrong"),Object(n.k)(t.response.data.error||"Something went wrong","error")}))}}},a=r("KHd+"),s=Object(a.a)(i,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"card setting-card"},[r("form",{on:{submit:function(e){return e.preventDefault(),t.save(e)}}},[r("h3",{staticClass:"with-button"},[t._v("\n            General Settings\n            "),r("button",{directives:[{name:"ripple",rawName:"v-ripple"}],staticClass:"style-none add-button",attrs:{disabled:t.process}},[t._v("\n                "+t._s(t.process?"Saving":"Save")+"\n                "),r("i",{staticClass:"space fas",class:t.process?"fa-spinner fa-spin":"fa-save"})])]),t._v(" "),r("div",{staticClass:"form-control"},[r("label",{attrs:{for:"api_ver"}},[t._v("API Version")]),t._v(" "),r("select",{directives:[{name:"model",rawName:"v-model",value:t.app.api_version,expression:"app.api_version"}],attrs:{name:"api_ver",id:"api_ver"},on:{change:function(e){var r=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.app,"api_version",e.target.multiple?r:r[0])}}},[r("option",{attrs:{value:"v1",disabled:""}},[t._v("Version 1 (NOT WORKING)")]),t._v(" "),r("option",{attrs:{value:"v15",disabled:""}},[t._v("Version 1.5 (NOT WORKING)")]),t._v(" "),r("option",{attrs:{value:"wrapper"}},[t._v("Wrapper API")]),t._v(" "),r("option",{attrs:{disabled:"",value:"v2"}},[t._v("Version 2 (Coming Soon)")])])]),t._v(" "),r("div",{staticClass:"form-control mt"},[r("label",{attrs:{for:"site_name"}},[t._v("Site Name")]),t._v(" "),r("input",{directives:[{name:"model",rawName:"v-model",value:t.app.name,expression:"app.name"}],attrs:{type:"site_name",id:"site_name",placeholder:"Enter Site Name",required:"",autofocus:""},domProps:{value:t.app.name},on:{input:function(e){e.target.composing||t.$set(t.app,"name",e.target.value)}}})]),t._v(" "),r("div",{staticClass:"form-control mt"},[r("label",{attrs:{for:"site_desc"}},[t._v("Site Description")]),t._v(" "),r("textarea",{directives:[{name:"model",rawName:"v-model",value:t.app.desc,expression:"app.desc"}],attrs:{name:"site_desc",id:"site_desc",required:"",cols:"5",rows:"5"},domProps:{value:t.app.desc},on:{input:function(e){e.target.composing||t.$set(t.app,"desc",e.target.value)}}})]),t._v(" "),r("div",{staticClass:"form-control mt"},[r("label",{attrs:{for:"site_keywords"}},[t._v("Site Keywords")]),t._v(" "),r("textarea",{directives:[{name:"model",rawName:"v-model",value:t.app.keywords,expression:"app.keywords"}],attrs:{name:"site_keywords",id:"site_keywords",required:"",cols:"5",rows:"2"},domProps:{value:t.app.keywords},on:{input:function(e){e.target.composing||t.$set(t.app,"keywords",e.target.value)}}})]),t._v(" "),r("h3",{staticClass:"mt with-button"},[t._v("Social Links\n            "),r("button",{staticClass:"style-none add-button",attrs:{disabled:Object.keys(t.socials).length>6},on:{click:function(e){return e.preventDefault(),t.addLink(e)}}},[r("i",{staticClass:"fas fa-plus"})])]),t._v(" "),r("div",{staticClass:"social-links"},[r("transition-group",{attrs:{"enter-active-class":"animated fadeIn d-3","leave-active-class":"animated fadeOut d-3"}},t._l(t.socials,(function(e,n){return r("div",{key:n,staticClass:"social-link-grid"},[r("div",{staticClass:"form-control"},[r("select",{directives:[{name:"model",rawName:"v-model",value:e.text,expression:"link.text"}],attrs:{name:e.text,id:e.text},on:{change:function(r){var n=Array.prototype.filter.call(r.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(e,"text",r.target.multiple?n:n[0])}}},t._l(t.brands,(function(e){return r("option",{attrs:{disabled:t.isSelected(e)},domProps:{value:e}},[t._v(t._s(t._f("capitalize")(e)))])})),0)]),t._v(" "),r("div",{staticClass:"form-control"},[r("input",{directives:[{name:"model",rawName:"v-model",value:e.link,expression:"link.link"}],attrs:{"aria-label":"Link Url",type:"text",name:"link_url",required:"",placeholder:"Enter Link Url"},domProps:{value:e.link},on:{input:function(r){r.target.composing||t.$set(e,"link",r.target.value)}}})]),t._v(" "),r("button",{staticClass:"style-none delete-button",on:{click:function(e){return e.preventDefault(),t.deleteLink(t.index)}}},[r("i",{staticClass:"fas fa-times"})])])})),0),t._v(" "),Object.keys(t.socials).length<1?r("div",{staticClass:"no-social-links"},[t._v("\n                No Social Links\n            ")]):t._e()],1)])])}),[],!1,null,null,null);e.default=s.exports},hXpO:function(t,e,r){var n=r("HYAF"),o=/"/g;t.exports=function(t,e,r,i){var a=String(n(t)),s="<"+e;return""!==r&&(s+=" "+r+'="'+String(i).replace(o,"&quot;")+'"'),s+">"+a+"</"+e+">"}},ls82:function(t,e,r){var n=function(t){"use strict";var e=Object.prototype,r=e.hasOwnProperty,n="function"==typeof Symbol?Symbol:{},o=n.iterator||"@@iterator",i=n.asyncIterator||"@@asyncIterator",a=n.toStringTag||"@@toStringTag";function s(t,e,r,n){var o=e&&e.prototype instanceof u?e:u,i=Object.create(o.prototype),a=new x(n||[]);return i._invoke=function(t,e,r){var n="suspendedStart";return function(o,i){if("executing"===n)throw new Error("Generator is already running");if("completed"===n){if("throw"===o)throw i;return L()}for(r.method=o,r.arg=i;;){var a=r.delegate;if(a){var s=w(a,r);if(s){if(s===l)continue;return s}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if("suspendedStart"===n)throw n="completed",r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n="executing";var u=c(t,e,r);if("normal"===u.type){if(n=r.done?"completed":"suspendedYield",u.arg===l)continue;return{value:u.arg,done:r.done}}"throw"===u.type&&(n="completed",r.method="throw",r.arg=u.arg)}}}(t,r,a),i}function c(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(t){return{type:"throw",arg:t}}}t.wrap=s;var l={};function u(){}function p(){}function f(){}var d={};d[o]=function(){return this};var v=Object.getPrototypeOf,h=v&&v(v(k([])));h&&h!==e&&r.call(h,o)&&(d=h);var m=f.prototype=u.prototype=Object.create(d);function g(t){["next","throw","return"].forEach((function(e){t[e]=function(t){return this._invoke(e,t)}}))}function y(t,e){var n;this._invoke=function(o,i){function a(){return new e((function(n,a){!function n(o,i,a,s){var l=c(t[o],t,i);if("throw"!==l.type){var u=l.arg,p=u.value;return p&&"object"==typeof p&&r.call(p,"__await")?e.resolve(p.__await).then((function(t){n("next",t,a,s)}),(function(t){n("throw",t,a,s)})):e.resolve(p).then((function(t){u.value=t,a(u)}),(function(t){return n("throw",t,a,s)}))}s(l.arg)}(o,i,n,a)}))}return n=n?n.then(a,a):a()}}function w(t,e){var r=t.iterator[e.method];if(void 0===r){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=void 0,w(t,e),"throw"===e.method))return l;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return l}var n=c(r,t.iterator,e.arg);if("throw"===n.type)return e.method="throw",e.arg=n.arg,e.delegate=null,l;var o=n.arg;return o?o.done?(e[t.resultName]=o.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=void 0),e.delegate=null,l):o:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,l)}function _(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function b(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function x(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(_,this),this.reset(!0)}function k(t){if(t){var e=t[o];if(e)return e.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var n=-1,i=function e(){for(;++n<t.length;)if(r.call(t,n))return e.value=t[n],e.done=!1,e;return e.value=void 0,e.done=!0,e};return i.next=i}}return{next:L}}function L(){return{value:void 0,done:!0}}return p.prototype=m.constructor=f,f.constructor=p,f[a]=p.displayName="GeneratorFunction",t.isGeneratorFunction=function(t){var e="function"==typeof t&&t.constructor;return!!e&&(e===p||"GeneratorFunction"===(e.displayName||e.name))},t.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,f):(t.__proto__=f,a in t||(t[a]="GeneratorFunction")),t.prototype=Object.create(m),t},t.awrap=function(t){return{__await:t}},g(y.prototype),y.prototype[i]=function(){return this},t.AsyncIterator=y,t.async=function(e,r,n,o,i){void 0===i&&(i=Promise);var a=new y(s(e,r,n,o),i);return t.isGeneratorFunction(r)?a:a.next().then((function(t){return t.done?t.value:a.next()}))},g(m),m[a]="Generator",m[o]=function(){return this},m.toString=function(){return"[object Generator]"},t.keys=function(t){var e=[];for(var r in t)e.push(r);return e.reverse(),function r(){for(;e.length;){var n=e.pop();if(n in t)return r.value=n,r.done=!1,r}return r.done=!0,r}},t.values=k,x.prototype={constructor:x,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(b),!t)for(var e in this)"t"===e.charAt(0)&&r.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=void 0)},stop:function(){this.done=!0;var t=this.tryEntries[0].completion;if("throw"===t.type)throw t.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function n(r,n){return a.type="throw",a.arg=t,e.next=r,n&&(e.method="next",e.arg=void 0),!!n}for(var o=this.tryEntries.length-1;o>=0;--o){var i=this.tryEntries[o],a=i.completion;if("root"===i.tryLoc)return n("end");if(i.tryLoc<=this.prev){var s=r.call(i,"catchLoc"),c=r.call(i,"finallyLoc");if(s&&c){if(this.prev<i.catchLoc)return n(i.catchLoc,!0);if(this.prev<i.finallyLoc)return n(i.finallyLoc)}else if(s){if(this.prev<i.catchLoc)return n(i.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return n(i.finallyLoc)}}}},abrupt:function(t,e){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var i=o;break}}i&&("break"===t||"continue"===t)&&i.tryLoc<=e&&e<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=t,a.arg=e,i?(this.method="next",this.next=i.finallyLoc,l):this.complete(a)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),l},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),b(r),l}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var o=n.arg;b(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,r){return this.delegate={iterator:k(t),resultName:e,nextLoc:r},"next"===this.method&&(this.arg=void 0),l}},t}(t.exports);try{regeneratorRuntime=n}catch(t){Function("r","regeneratorRuntime = r")(n)}},mRH6:function(t,e,r){"use strict";var n=r("I+eb"),o=r("hXpO");n({target:"String",proto:!0,forced:r("rwPt")("link")},{link:function(t){return o(this,"a","href",t)}})},pDQq:function(t,e,r){"use strict";var n=r("I+eb"),o=r("I8vh"),i=r("ppGB"),a=r("UMSQ"),s=r("ewvW"),c=r("ZfDv"),l=r("hBjN"),u=r("Hd5f"),p=r("rkAj"),f=u("splice"),d=p("splice",{ACCESSORS:!0,0:0,1:2}),v=Math.max,h=Math.min;n({target:"Array",proto:!0,forced:!f||!d},{splice:function(t,e){var r,n,u,p,f,d,m=s(this),g=a(m.length),y=o(t,g),w=arguments.length;if(0===w?r=n=0:1===w?(r=0,n=g-y):(r=w-2,n=h(v(i(e),0),g-y)),g+r-n>9007199254740991)throw TypeError("Maximum allowed length exceeded");for(u=c(m,n),p=0;p<n;p++)(f=y+p)in m&&l(u,p,m[f]);if(u.length=n,r<n){for(p=y;p<g-n;p++)d=p+r,(f=p+n)in m?m[d]=m[f]:delete m[d];for(p=g;p>g-n+r;p--)delete m[p-1]}else if(r>n)for(p=g-n;p>y;p--)d=p+r-1,(f=p+n-1)in m?m[d]=m[f]:delete m[d];for(p=0;p<r;p++)m[p+y]=arguments[p+2];return m.length=g-n+r,u}})},rwPt:function(t,e,r){var n=r("0Dky");t.exports=function(t){return n((function(){var e=""[t]('"');return e!==e.toLowerCase()||e.split('"').length>3}))}}}]);