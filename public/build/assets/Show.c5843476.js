import{D as r}from"./DiscussionBoard.958d96d7.js";import{_ as h,c as u,a as o,b as s,y as d,t as e,F as f,f as n,o as g,d as l}from"./app.4060d334.js";import"./Pagination.484cfb51.js";import"./AttachmentList.af85f6f3.js";import"./bootstrap.esm.c86673ca.js";const p={props:["data","messages"],components:{DiscussionBoard:r}},v={class:"container page-container"},b={class:"row"},T={class:"col-md-12"},w={class:"d-flex justify-content-between flex-column d-sm-flex flex-sm-row"},D=s("i",{class:"fa-solid fa-arrow-left-long"},null,-1),k={class:"mb-4 mt-4"},B=s("hr",null,null,-1),x={class:"text-muted"},y={class:"text-muted"},C={class:"row"},L={class:"col-md-3"},S={class:"text-muted"},j={class:"mt-4"},N={class:"text-muted"},V={class:"mt-4"},A={class:"text-muted"},F={class:"col-md-9"};function H(t,R,a,E,M,q){const c=n("AppHead"),i=n("Link"),_=n("DialogLink"),m=n("DiscussionBoard");return g(),u(f,null,[o(c,{title:a.data.title},null,8,["title"]),s("div",v,[s("div",b,[s("div",T,[s("div",w,[s("div",null,[o(i,{class:"btn btn-sm btn-light",href:t.route("author.messageThreads.index")},{default:d(()=>[D,l(" "+e(t.__("Back to Message Threads")),1)]),_:1},8,["href"])]),s("div",null,[o(_,{href:t.route("author.messageThreads.messages.create",a.data.messageThread.uuid),class:"btn btn-sm btn-primary"},{default:d(()=>[l(e(t.__("Send Reply")),1)]),_:1},8,["href"])])])])]),s("div",k,[B,s("small",x,e(t.__("Subject"))+" : ",1),s("h3",y,e(a.data.messageThread.subject),1)]),s("div",C,[s("div",L,[s("div",null,e(t.__("Created at"))+":",1),s("small",S,e(t.localDate(a.data.messageThread.created_at)),1),s("div",j,e(t.__("Created by"))+":",1),s("small",N,e(a.data.messageThread.sender.full_name),1),s("div",V,e(t.__("Recipient"))+":",1),s("small",A,e(a.data.messageThread.recipient.full_name),1)]),s("div",F,[o(m,{messages:a.messages,data:a.data,show_name:!0},null,8,["messages","data"])])])])],64)}const O=h(p,[["render",H]]);export{O as default};