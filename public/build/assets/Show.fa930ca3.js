import k from"./TaskShowLayout.1ce61710.js";import{A as b}from"./AttachmentList.af85f6f3.js";import{T}from"./TaskBrief.e3b9612f.js";import{_ as g,c as B,a as s,b as e,y as l,t as n,F as A,f as o,o as v,d as r}from"./app.4060d334.js";import"./Countdown.5167b0c4.js";import"./bootstrap.esm.c86673ca.js";const w={props:["task","data"],components:{TaskShowLayout:k,TaskBrief:T,AttachmentList:b},data(){return{commonBriefs:[{label:this.__("Created"),value:this.localDateTime(this.task.created_at)},{label:this.__("Deadline"),value:this.task.dead_line_for_author?this.localDateTime(this.task.dead_line_for_author):null},{label:this.__("Earning"),value:this.formatMoney(this.task.author_payment_amount)}]}},methods:{acceptTask(){let t=this;this.confirmDialog(this.__("Accept"),function(){t.$inertia.post(route("author.find.work.accept",t.task.uuid))})}}},y={class:"container page-container"},L=e("i",{class:"fa-solid fa-arrow-left-long"},null,-1),D=e("i",{class:"fa-sharp fa-solid fa-rocket"},null,-1),C={class:"row mt-4"},F={class:"col-md-12"},N={class:"mb-4 bg-light p-2"};function S(t,i,a,V,_,c){const m=o("AppHead"),h=o("Link"),d=o("PageTitle"),f=o("TaskBrief"),u=o("AttachmentList");return v(),B(A,null,[s(m,{title:a.data.title},null,8,["title"]),e("div",y,[s(d,{title:a.data.title},{default:l(()=>[s(h,{class:"btn btn-sm btn-light me-2",href:t.route("author.find.work.index")},{default:l(()=>[L,r(" "+n(t.__("Find other Tasks")),1)]),_:1},8,["href"]),e("button",{type:"button",class:"btn btn-sm btn-success",onClick:i[0]||(i[0]=(...p)=>c.acceptTask&&c.acceptTask(...p))},[D,r(" "+n(t.__("Accept this task")),1)])]),_:1},8,["title"]),s(f,{briefs:a.data.briefs,task:a.task,commonBriefs:_.commonBriefs},null,8,["briefs","task","commonBriefs"]),e("div",C,[e("div",F,[e("h5",N,n(t.__("Attachments")),1),s(u,{attachments:a.task.attachments},null,8,["attachments"])])])])],64)}const q=g(w,[["render",S]]);export{q as default};