import r from"./BidLayout.d91df6c6.js";import{T as i}from"./TaskBrief.e3b9612f.js";import{_ as n,g as c,y as _,f as t,o as f,a as m}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const d={props:["bid_request","data","task"],components:{BidLayout:r,TaskBrief:i},data(){return{commonBriefs:[{label:this.__("Created"),value:this.localDateTime(this.bid_request.created_at)},{label:this.__("Budget"),value:this.formatMoney(this.bid_request.budget)}]}}};function u(l,b,e,B,a,p){const o=t("TaskBrief"),s=t("BidLayout");return f(),c(s,{bid_request:e.bid_request,activeTab:"brief"},{default:_(()=>[m(o,{briefs:e.data.briefs,task:e.task,commonBriefs:a.commonBriefs},null,8,["briefs","task","commonBriefs"])]),_:1},8,["bid_request"])}const y=n(d,[["render",u]]);export{y as default};