import{_ as d,c as n,a as c,b as e,t,d as a,y as u,h,F as m,f as r,o as i}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const f={props:["data"]},p={class:"container page-container"},k={class:"row"},y={class:"col-md-12"},v=e("hr",null,null,-1),C={class:"text-center"},g=e("i",{class:"fas fa-check-circle text-success"},null,-1),N={class:"mt-2 mb-4"},V={key:0};function B(s,b,o,w,x,A){const l=r("AppHead"),_=r("Link");return i(),n(m,null,[c(l,{title:o.data.title},null,8,["title"]),e("div",p,[e("div",k,[e("div",y,[e("h3",null,t(o.data.title),1),v,e("div",C,[e("h2",null,[g,a(" "+t(s.__("Thank you for your order"))+"! ",1)]),e("div",N,t(s.__("Check your email for your receipt")),1),o.data.task_url?(i(),n("p",V,[a(t(s.__("Click"))+" ",1),c(_,{href:o.data.task_url},{default:u(()=>[a(t(s.__("here")),1)]),_:1},8,["href"]),a(" "+t(s.__("to visit your task page")),1)])):h("",!0)])])])])],64)}const L=d(f,[["render",B]]);export{L as default};