import{T}from"./Table.afdff20f.js";import{S as N}from"./SearchBar.fdc86cb9.js";import{_ as g,c,a as n,b as t,y as l,F as d,f as a,o as r,d as m,t as i,r as L}from"./app.4060d334.js";import"./Pagination.484cfb51.js";import"./debounce.b911da70.js";import"./bootstrap.esm.c86673ca.js";const v={components:{Table:T,SearchBar:N},props:["data","announcements","filters"],data(){return{tableOptions:{titles:[{name:this.__("Number"),className:"col-md-2"},{name:this.__("Title"),className:"col-md-8"},{name:this.__("Created"),className:"col-md-2"}]}}}},w={class:"container page-container"},y={class:"row"},B={class:"col-md-12"},D=t("i",{class:"fa-solid fa-plus"},null,-1);function S(s,A,e,C,_,V){const u=a("AppHead"),p=a("DialogLink"),f=a("PageTitle"),h=a("Link"),b=a("Table");return r(),c(d,null,[n(u,{title:e.data.title},null,8,["title"]),t("div",w,[t("div",y,[t("div",B,[n(f,{title:e.data.title},{default:l(()=>[n(p,{class:"btn btn-sm btn-outline-success me-2",href:s.route("admin.announcements.create")},{default:l(()=>[D,m(" "+i(s.__("New Announcement")),1)]),_:1},8,["href"])]),_:1},8,["title"]),n(b,{options:_.tableOptions,links:e.announcements.links,total:e.announcements.total,tableStyle:"fs-8"},{default:l(()=>[(r(!0),c(d,null,L(e.announcements.data,(o,k)=>(r(),c("tr",{key:k},[t("td",null,[n(h,{href:s.route("admin.announcements.show",o.uuid)},{default:l(()=>[m(i(o.number),1)]),_:2},1032,["href"])]),t("td",null,i(o.title),1),t("td",null,i(s.localDateTime(o.created_at)),1)]))),128))]),_:1},8,["options","links","total"])])])])],64)}const I=g(v,[["render",S]]);export{I as default};