import p from"./TaskShowLayout.1ce61710.js";import{C as g}from"./CommentMessage.d5bfa457.js";import{S as f}from"./SendMessage.7440976e.js";import{_ as y,g as r,y as _,f as c,o as s,b as e,d as v,t as o,h as n,c as l,r as S,n as u,a as w,F as C}from"./app.4060d334.js";import"./Countdown.5167b0c4.js";import"./bootstrap.esm.c86673ca.js";import"./AttachmentList.af85f6f3.js";import"./Attachment.a35ebc48.js";import"./vue-quill.snow.151e10a0.js";const q={props:["tab","task","data","works"],components:{TaskShowLayout:p,CommentMessage:g,SendMessage:f},computed:{isSubmitWorkButtonEnabled(){return this.data.statuses_allows_submitting_work.includes(this.task.task_status_id)}},updated(){this.displaySubmitWork=!1},data(){return{displaySubmitWork:!1}},methods:{handleAttachment(t){this.form.files=t}}},B={class:"d-flex justify-content-between"},L=e("div",null,null,-1),T=e("i",{class:"fas fa-file-upload"},null,-1),N={key:0,class:"h4 text-center bg-light"},W={class:"row g-0"},M={class:"bg-light p-2"},V={key:0,class:"col-md-4"},A={class:"bg-light p-2"},D={class:"bg-light p-2"},E={key:0,class:"text-center"};function F(t,j,a,z,I,h){const k=c("DialogLink"),m=c("CommentMessage"),b=c("TaskShowLayout");return s(),r(b,{task:a.task,activeTab:a.tab},{default:_(()=>[e("div",B,[L,e("div",null,[h.isSubmitWorkButtonEnabled?(s(),r(k,{key:0,href:a.data.urls.submit_work,class:"btn btn-sm btn-primary me-2"},{default:_(()=>[T,v(" "+o(t.__("Submit Work")),1)]),_:1},8,["href"])):n("",!0)])]),(s(!0),l(C,null,S(a.works,(i,d)=>(s(),l("div",{class:"btask mt-2 mb-5 fs-8",key:d},[a.works.length>1?(s(),l("div",N,o(t.__("Submission"))+" : "+o(a.works.length-d),1)):n("",!0),e("div",W,[e("div",{class:u(t.$page.props.is_quality_control_enable?"col-md-4":"col-md-6")},[e("div",M,o(t.__("Author")),1),w(m,{comment:i},null,8,["comment"])],2),t.$page.props.is_quality_control_enable?(s(),l("div",V,[e("div",A,o(t.__("QA")),1),i.quality_assurance?(s(),r(m,{key:0,comment:i.quality_assurance},null,8,["comment"])):n("",!0)])):n("",!0),e("div",{class:u(t.$page.props.is_quality_control_enable?"col-md-4":"col-md-6")},[e("div",D,o(t.__("Customer")),1),i.revision_request?(s(),r(m,{key:0,comment:i.revision_request},null,8,["comment"])):n("",!0)],2)])]))),128)),a.works.length==0?(s(),l("div",E,o(t.__("No work has been submitted yet")),1)):n("",!0)]),_:1},8,["task","activeTab"])}const X=y(q,[["render",F]]);export{X as default};
