import k from"./BusinessLayout.edb70bcb.js";import{S as g}from"./SearchBar.fdc86cb9.js";import T from"./TitleBar.08f288ed.js";import{D as x}from"./DestroyButton.67b6d41c.js";import{T as N}from"./Table.afdff20f.js";import{_ as b,g as y,y as s,f as t,o as r,a as n,c as l,r as L,b as i,d as D,t as c,F as S}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";import"./Pagination.484cfb51.js";const F={components:{BusinessLayout:k,SearchBar:g,Table:N,TitleBar:T,DestroyButton:x},props:["data","urgencies","filters"],data(){return{tableOptions:{titles:[{name:this.__("Name"),className:"col-md-6"},{name:this.__("Markup"),className:"col-md-4 text-end"},{name:this.__("Action"),className:"col-md-2 text-end"}]}}}},V={class:"text-end"},v={class:"col-md-2 text-end"};function C(o,O,e,w,d,A){const m=t("TitleBar"),u=t("SearchBar"),_=t("Link"),p=t("DestroyButton"),f=t("Table"),B=t("BusinessLayout");return r(),y(B,{title:e.data.title},{default:s(()=>[n(m,{title:e.data.title,create_link:o.route("admin.urgencies.create")},null,8,["title","create_link"]),n(u,{url:o.route("admin.urgencies.index"),filters:e.filters.filters,hide_inactive_search:!0},null,8,["url","filters"]),n(f,{options:d.tableOptions,links:e.urgencies.links,total:e.urgencies.total},{default:s(()=>[(r(!0),l(S,null,L(e.urgencies.data,(a,h)=>(r(),l("tr",{key:h},[i("td",null,[n(_,{href:o.route("admin.urgencies.edit",a.id)},{default:s(()=>[D(c(a.name),1)]),_:2},1032,["href"])]),i("td",V,c(a.percentage?parseFloat(a.percentage):0)+"% ",1),i("td",v,[n(p,{delete_url:o.route("admin.urgencies.destroy",a.id)},null,8,["delete_url"])])]))),128))]),_:1},8,["options","links","total"])]),_:1},8,["title"])}const K=b(F,[["render",C]]);export{K as default};