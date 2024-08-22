import B from"./BusinessLayout.edb70bcb.js";import{S as g}from"./SearchBar.fdc86cb9.js";import h from"./TitleBar.08f288ed.js";import{D as L}from"./DestroyButton.67b6d41c.js";import{T as y}from"./Table.afdff20f.js";import{_ as T,g as x,y as n,f as a,o,a as i,c as l,r as N,b as r,d as b,t as c,F as D}from"./app.4060d334.js";import"./debounce.b911da70.js";import"./Pagination.484cfb51.js";import"./bootstrap.esm.c86673ca.js";const S={components:{BusinessLayout:B,SearchBar:g,Table:y,TitleBar:h,DestroyButton:L},props:["data","academic_levels","filters"],data(){return{tableOptions:{titles:[{name:this.__("Name"),className:"col-md-6"},{name:this.__("Markup"),className:"col-md-4 text-end"},{name:this.__("Action"),className:"col-md-2 text-end"}]}}}},F={class:"text-end"},V={class:"col-md-2 text-end"};function C(t,O,e,w,d,A){const m=a("TitleBar"),u=a("SearchBar"),_=a("Link"),f=a("DestroyButton"),v=a("Table"),k=a("BusinessLayout");return o(),x(k,{title:e.data.title},{default:n(()=>[i(m,{title:e.data.title,create_link:t.route("admin.academicLevels.create",{service:e.data.service.slug}),previous_link:t.route("admin.services.configurationHome",e.data.service.slug),previous_link_text:t.__("Back to configuration")},null,8,["title","create_link","previous_link","previous_link_text"]),i(u,{url:t.route("admin.academicLevels.index",{service:e.data.service.slug}),filters:e.filters.filters,hide_inactive_search:!0},null,8,["url","filters"]),i(v,{options:d.tableOptions,links:e.academic_levels.links,total:e.academic_levels.total},{default:n(()=>[(o(!0),l(D,null,N(e.academic_levels.data,(s,p)=>(o(),l("tr",{key:p},[r("td",null,[i(_,{href:t.route("admin.academicLevels.edit",{service:e.data.service.slug,academicLevel:s.slug})},{default:n(()=>[b(c(s.name),1)]),_:2},1032,["href"])]),r("td",F,c(s.percentage?parseFloat(s.percentage):0)+"% ",1),r("td",V,[i(f,{delete_url:t.route("admin.academicLevels.destroy",{service:e.data.service.slug,academicLevel:s.slug})},null,8,["delete_url"])])]))),128))]),_:1},8,["options","links","total"])]),_:1},8,["title"])}const K=T(S,[["render",C]]);export{K as default};