import{T}from"./Table.afdff20f.js";import N from"./Search.f294d3b2.js";import{_ as k,c as i,a as o,b as t,y as r,F as d,f as n,o as c,r as v,d as D,t as l}from"./app.4060d334.js";import"./Pagination.484cfb51.js";import"./Select.3a870b54.js";import"./SearchButton.5ef8bad5.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./debounce.b911da70.js";import"./DateRangePicker.6ca75690.js";import"./bootstrap.esm.c86673ca.js";const S={props:["data","invoices","filters"],components:{Table:T,Search:N},data(){return{tableOptions:{titles:[{name:this.__("Number"),className:""},{name:this.__("Date"),className:""},{name:this.__("Due Date"),className:""},{name:this.__("Status"),className:""},{name:this.__("Total"),className:"text-end"}]}}},methods:{getStatusColor(a){this.data.ribbon_bg_colors[a]}}},x={class:"container page-container"},y={class:"row"},w={class:"col-md-3"},B={class:"col-md-9"},C={class:""},I={class:"text-end"};function L(a,V,e,A,_,F){const m=n("AppHead"),u=n("PageTitle"),p=n("Search"),h=n("Link"),f=n("InlineTags"),b=n("Table");return c(),i(d,null,[o(m,{title:e.data.title},null,8,["title"]),t("div",x,[o(u,{title:e.data.title},null,8,["title"]),t("div",y,[t("div",w,[o(p,{data:e.data,filters:e.filters},null,8,["data","filters"])]),t("div",B,[o(b,{options:_.tableOptions,links:e.invoices.links,total:e.invoices.total},{default:r(()=>[(c(!0),i(d,null,v(e.invoices.data,(s,g)=>(c(),i("tr",{key:g},[t("td",null,[o(h,{href:a.route("customer.invoices.show",s.uuid)},{default:r(()=>[D(l(s.number),1)]),_:2},1032,["href"])]),t("td",null,l(a.localDate(s.invoice_date)),1),t("td",C,l(a.localDate(s.due_date)),1),t("td",null,[o(f,{tags:[s.status]},null,8,["tags"])]),t("td",I,l(a.formatMoney(s.total)),1)]))),128))]),_:1},8,["options","links","total"])])])])],64)}const K=k(S,[["render",L]]);export{K as default};
