import b from"./TaskShowLayout.c6774e5f.js";import{T as f}from"./Table.afdff20f.js";import{_ as k,a3 as y,g as w,y as n,f as l,o as d,b as t,t as a,a as c,d as g,c as r,r as v,F as T}from"./app.4060d334.js";import"./Countdown.5167b0c4.js";import"./Pagination.484cfb51.js";import"./bootstrap.esm.c86673ca.js";const S={props:["task","data","attachments"],components:{TaskShowLayout:b,Table:f,DialogLink:y},data(){return{tableStyle:"table-sm",options:{titles:[{name:this.__("Name"),className:"col-md-5"},{name:this.__("Created"),className:"col-md-5"},{name:null,className:"col-md-2 text-end"}]}}},methods:{handleAttachment(e){this.form.files=e},deleteAttachment(e){this.deleteConfirmDialog(this,route("admin.tasks.attachments.destroy",[this.task.uuid,e]))}}},D={class:"d-flex justify-content-between mb-4"},L={class:"text-end"},N={class:"btn-group"},C=t("button",{type:"button",class:"btn btn-link","data-bs-toggle":"dropdown","aria-expanded":"false"},[t("i",{class:"fas fa-ellipsis-v"})],-1),x={class:"dropdown-menu dropdown-menu-end"},A={class:"dropdown-item",type:"button"},B=["href"],F=["onClick"];function V(e,j,s,E,i,m){const _=l("DialogLink"),u=l("Table"),h=l("TaskShowLayout");return d(),w(h,{task:s.task,activeTab:"attachment"},{default:n(()=>[t("div",D,[t("div",null,[t("h4",null,a(e.__("Attachments")),1)]),t("div",null,[c(_,{href:s.data.urls.create_attachment,class:"btn btn-sm btn-primary"},{default:n(()=>[g(a(e.__("Upload File")),1)]),_:1},8,["href"])])]),c(u,{tableStyle:i.tableStyle,options:i.options,links:s.attachments.link,total:s.attachments.total},{default:n(()=>[(d(!0),r(T,null,v(s.attachments.data,(o,p)=>(d(),r("tr",{key:p},[t("td",null,a(o.display_name),1),t("td",null,a(e.localDate(o.created_at)),1),t("td",L,[t("div",N,[C,t("ul",x,[t("li",null,[t("button",A,[t("a",{href:e.route("attachments.download",o.uuid)},a(e.__("Download")),9,B)])]),t("li",null,[t("button",{onClick:I=>m.deleteAttachment(o.uuid),class:"dropdown-item",type:"button"},a(e.__("Remove")),9,F)])])])])]))),128))]),_:1},8,["tableStyle","options","links","total"])]),_:1},8,["task"])}const J=k(S,[["render",V]]);export{J as default};