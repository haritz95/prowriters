import{_ as i,c as d,a as o,b as t,y as _,t as n,F as m,f as a,o as r,d as u}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const h={props:["data","announcement"]},f={class:"container page-container"},p={class:"row"},x={class:"col-md-12"},v={class:"d-flex justify-content-between flex-column d-sm-flex flex-sm-row"},b=t("i",{class:"fa-solid fa-arrow-left-long"},null,-1),w={class:"mb-4 mt-4"},g=t("hr",null,null,-1),k={class:"text-muted"},T={class:"text-muted"},B={class:"text-muted"},H={class:"text-muted"},L=["innerHTML"];function y(e,A,s,C,N,V){const c=a("AppHead"),l=a("Link");return r(),d(m,null,[o(c,{title:s.data.title},null,8,["title"]),t("div",f,[t("div",p,[t("div",x,[t("div",v,[o(l,{class:"btn btn-sm btn-light",href:e.route("author.announcements.index")},{default:_(()=>[b,u(" "+n(e.__("Back to Announcements")),1)]),_:1},8,["href"])])])]),t("div",w,[g,t("small",k,n(e.__("Title"))+" : ",1),t("h3",T,n(s.announcement.title),1),t("small",B,n(e.localDateTime(s.announcement.created_at)),1)]),t("small",H,n(e.__("Content"))+" : ",1),t("div",{innerHTML:s.announcement.content},null,8,L)])],64)}const M=i(h,[["render",y]]);export{M as default};
