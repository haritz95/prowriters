import m from"./TaskShowLayout.c6774e5f.js";import{D as _}from"./DiscussionBoard.958d96d7.js";import{_ as u,g as f,y as n,f as e,o as i,b as a,t,c as k,h as p,a as c,d as g}from"./app.4060d334.js";import"./Countdown.5167b0c4.js";import"./bootstrap.esm.c86673ca.js";import"./Pagination.484cfb51.js";import"./AttachmentList.af85f6f3.js";const h={props:["task","data","messages","tab"],components:{TaskShowLayout:m,DiscussionBoard:_}},b={class:"d-flex justify-content-between"},y={key:0,class:"text-muted"};function w(o,B,s,D,v,x){const r=e("DialogLink"),d=e("DiscussionBoard"),l=e("TaskShowLayout");return i(),f(l,{task:s.task,activeTab:s.tab},{default:n(()=>[a("div",b,[a("div",null,[a("h4",null,t(o.__("Messages")),1),s.data.discussion_parties?(i(),k("p",y,[a("small",null,t(s.data.discussion_parties),1)])):p("",!0)]),a("div",null,[c(r,{href:s.data.urls.new_message,class:"btn btn-sm btn-primary"},{default:n(()=>[g(t(o.__("New Message")),1)]),_:1},8,["href"])])]),c(d,{messages:s.messages,data:s.data},null,8,["messages","data"])]),_:1},8,["task","activeTab"])}const j=u(h,[["render",w]]);export{j as default};
