import h from"./SettingsLayout.4c1fc6b3.js";import{D as y}from"./DestroyButton.67b6d41c.js";import k from"./ActionToolBar.efa9c694.js";import{T as B}from"./Table.afdff20f.js";import{_ as g,g as T,y as a,f as e,o as i,a as n,c,r as N,b as r,d as x,t as d,F as L}from"./app.4060d334.js";import"./Menu.e7c29a6b.js";import"./bootstrap.esm.c86673ca.js";import"./Pagination.484cfb51.js";const A={components:{SettingsLayout:h,ActionToolBar:k,Table:B,DestroyButton:y},props:["data","subscription_plans","filters"],data(){return{toolbar:{title:this.data.title,hide_save_button:!0,links:{create:{title:this.__("Add new"),url:route("admin.settings.subscriptionPlans.create")}}},tableOptions:{titles:[{name:this.__("Name"),className:"col-md-2"},{name:this.__("Price"),className:"col-md-2 text-end"},{name:this.__("Action"),className:"col-md-2 text-end"}]}}}},D={class:"text-end"},P={class:"col-md-2 text-end"};function S(o,F,s,V,l,v){const m=e("ActionToolBar"),_=e("Link"),u=e("DestroyButton"),p=e("Table"),f=e("SettingsLayout");return i(),T(f,{title:s.data.title},{default:a(()=>[n(m,{toolbar:l.toolbar},null,8,["toolbar"]),n(p,{options:l.tableOptions,links:s.subscription_plans.links,total:s.subscription_plans.total},{default:a(()=>[(i(!0),c(L,null,N(s.subscription_plans.data,(t,b)=>(i(),c("tr",{key:b},[r("td",null,[n(_,{href:o.route("admin.settings.subscriptionPlans.edit",t.uuid)},{default:a(()=>[x(d(t.title),1)]),_:2},1032,["href"])]),r("td",D,d(t.price?o.formatMoney(t.price):o.__("Free")),1),r("td",P,[n(u,{delete_url:o.route("admin.settings.subscriptionPlans.destroy",t.id)},null,8,["delete_url"])])]))),128))]),_:1},8,["options","links","total"])]),_:1},8,["title"])}const z=g(A,[["render",S]]);export{z as default};
