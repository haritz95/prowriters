import N from"./Search.4b1da930.js";import{T}from"./Table.afdff20f.js";import{_ as y,c as n,a,b as t,y as d,F as _,f as o,o as i,r as B,d as r,t as l,h as m}from"./app.4060d334.js";import"./Input.47b7b4a9.js";import"./CheckBox.949c07b7.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./debounce.b911da70.js";import"./Pagination.484cfb51.js";import"./bootstrap.esm.c86673ca.js";const x={props:["data","users","filters"],components:{Table:T,Search:N},data(){return{tableOptions:{titles:[{name:"",className:""},{name:this.__("Name"),className:""},{name:this.__("Email"),className:""},{name:this.__("Last login"),className:""}]}}}},A={class:"container page-container"},L={class:"row"},S={class:"col-md-3"},V={class:"col-md-9"},w={class:"align-middle"},C=["src"],D={class:"align-middle"},E={key:0,class:"mt-2"},F={class:"badge text-bg-light"},H={key:1,class:"mt-2"},I={class:"badge text-bg-danger"},O={class:"align-middle"},P=t("i",{class:"fa-regular fa-envelope"},null,-1),j={class:"mt-2"},q=t("i",{class:"fa-solid fa-phone"},null,-1),z={class:"align-middle"};function G(c,J,s,K,h,M){const u=o("AppHead"),f=o("AddButton"),p=o("PageTitle"),g=o("Search"),v=o("Link"),b=o("Table");return i(),n(_,null,[a(u,{title:s.data.title},null,8,["title"]),t("div",A,[a(p,{title:s.data.title},{default:d(()=>[a(f,{href:c.route("admin.users.create")},null,8,["href"])]),_:1},8,["title"]),t("div",L,[t("div",S,[a(g,{data:s.data,filters:s.filters.filters,only:["users","filters"]},null,8,["data","filters"])]),t("div",V,[a(b,{options:h.tableOptions,links:s.users.links,total:s.users.total,only:["users","filters"]},{default:d(()=>[(i(!0),n(_,null,B(s.users.data,(e,k)=>(i(),n("tr",{key:k},[t("td",w,[t("img",{src:e.small_avatar,class:"avatar rounded-circle"},null,8,C)]),t("td",D,[a(v,{href:c.route("admin.users.show",e.uuid)},{default:d(()=>[r(l(e.full_name),1)]),_:2},1032,["href"]),e.roles.length>0?(i(),n("div",E,[t("small",F,l(s.data.roles[e.roles[0].name]),1)])):m("",!0),e.inactive?(i(),n("div",H,[t("span",I,l(c.__("Inactive")),1)])):m("",!0)]),t("td",O,[t("div",null,[P,r(" "+l(e.email),1)]),t("div",j,[q,r(" "+l(e.phone),1)])]),t("td",z,l(c.localDateTime(e.last_login_at)),1)]))),128))]),_:1},8,["options","links","total"])])])])],64)}const et=y(x,[["render",G]]);export{et as default};
