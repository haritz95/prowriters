import y from"./TaskShowLayout.c6774e5f.js";import g from"./Details.afe8d7c7.js";import{_ as v,g as l,y as a,f as c,o as i,b as s,a as r,d as n,t as o,h as u,c as w}from"./app.4060d334.js";import"./Countdown.5167b0c4.js";import"./bootstrap.esm.c86673ca.js";const D={props:["task","data"],components:{TaskShowLayout:y,Details:g},methods:{destroy(){this.deleteConfirmDialog(this,route("admin.tasks.destroy",this.task.uuid))}}},C={class:"d-flex justify-content-between flex-sm-row flex-column mb-5"},L=s("i",{class:"fa-solid fa-arrow-left-long"},null,-1),S=s("i",{class:"fa-solid fa-bell"},null,-1),T=s("i",{class:"fa-solid fa-bell"},null,-1),B={class:"d-flex flex-row-reverse bd-highlight mb-2"},A=s("i",{class:"fa-regular fa-clock"},null,-1),N=s("i",{class:"fa-solid fa-thumbtack"},null,-1),V=s("i",{class:"fa-solid fa-bars-progress"},null,-1),q=s("i",{class:"fa-regular fa-pen-to-square"},null,-1),E=["href"],F=s("i",{class:"fa-solid fa-trash-can"},null,-1),U=s("i",{class:"fa-solid fa-box-archive"},null,-1),j=s("i",{class:"fa-solid fa-box-open"},null,-1),I=s("i",{class:"fa-solid fa-pen-to-square"},null,-1),z=s("i",{class:"fa-solid fa-file-invoice"},null,-1);function G(e,m,t,H,J,h){const d=c("Link"),f=c("DialogLink"),_=c("Details"),k=c("TaskShowLayout");return i(),l(k,{task:t.task,activeTab:"general"},{default:a(()=>[s("div",C,[s("div",null,[r(d,{class:"btn btn-sm btn-light",href:e.route("admin.tasks.index")},{default:a(()=>[L,n(" "+o(e.__("Back to Tasks")),1)]),_:1},8,["href"]),t.data.is_a_follower?(i(),l(d,{key:0,class:"btn btn-sm btn-primary ms-2",href:e.route("admin.tasks.unFollow",t.task.uuid)},{default:a(()=>[S,n(" "+o(e.__("Unfollow")),1)]),_:1},8,["href"])):(i(),l(d,{key:1,class:"btn btn-sm btn-outline-primary ms-2",href:e.route("admin.tasks.follow",t.task.uuid)},{default:a(()=>[T,n(" "+o(e.__("Follow")),1)]),_:1},8,["href"]))]),s("div",B,[r(f,{class:"btn btn-sm btn-outline-success me-2",href:e.route("admin.tasks.edit.dates",t.task.uuid)},{default:a(()=>[A,n(" "+o(e.__("Change Deadline")),1)]),_:1},8,["href"]),r(f,{href:e.route("admin.tasks.edit.assignee",t.task.uuid),class:"btn btn-sm btn-outline-success me-2"},{default:a(()=>[N,n(" "+o(e.__("Assign Author")),1)]),_:1},8,["href"]),r(f,{href:e.route("admin.tasks.edit.status",t.task.uuid),class:"btn btn-sm btn-outline-success me-2"},{default:a(()=>[V,n(" "+o(e.__("Change Status")),1)]),_:1},8,["href"]),e.$page.props.is_quality_control_enable?(i(),l(f,{key:0,href:e.route("admin.tasks.edit.editor",t.task.uuid),class:"btn btn-sm btn-outline-success me-2"},{default:a(()=>[q,n(" "+o(e.__("Assign Editor")),1)]),_:1},8,["href"])):u("",!0),t.task.invoice_id?u("",!0):(i(),w("button",{key:1,onClick:m[0]||(m[0]=(...b)=>h.destroy&&h.destroy(...b)),class:"btn btn-sm btn-outline-danger me-2",href:e.route("admin.tasks.edit",t.task.uuid)},[F,n(" "+o(e.__("Delete")),1)],8,E)),t.data.allow.archiving?(i(),l(d,{key:2,class:"btn btn-sm btn-outline-secondary me-2",href:e.route("admin.tasks.archive",t.task.uuid),method:"post",as:"button",type:"button","preserve-scroll":""},{default:a(()=>[U,n(" "+o(e.__("Archive")),1)]),_:1},8,["href"])):u("",!0),t.data.allow.unarchiving?(i(),l(d,{key:3,class:"btn btn-sm btn-outline-warning me-2",href:e.route("admin.tasks.unarchive",t.task.uuid),method:"post",as:"button",type:"button","preserve-scroll":""},{default:a(()=>[j,n(" "+o(e.__("Unarchive")),1)]),_:1},8,["href"])):u("",!0),t.task.invoice_id?u("",!0):(i(),l(d,{key:4,class:"btn btn-sm btn-outline-warning me-2",href:e.route("admin.tasks.edit",t.task.uuid)},{default:a(()=>[I,n(" "+o(e.__("Edit")),1)]),_:1},8,["href"])),t.task.invoice_id?u("",!0):(i(),l(d,{key:5,class:"btn btn-sm btn-outline-primary me-2",href:e.route("admin.invoices.create",{customer_id:t.task.customer_id})},{default:a(()=>[z,n(" "+o(e.__("Create Invoice")),1)]),_:1},8,["href"]))])]),r(_,{task:t.task,data:t.data},null,8,["task","data"])]),_:1},8,["task"])}const R=v(D,[["render",G]]);export{R as default};