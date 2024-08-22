import{T as y}from"./Table.afdff20f.js";import N from"./Search.25ec9a9c.js";import{_ as v,c as l,a as d,b as t,t as s,y as c,h as T,F as r,f as i,o as n,r as p,d as f}from"./app.4060d334.js";import"./Pagination.484cfb51.js";import"./Input.47b7b4a9.js";import"./CheckBox.949c07b7.js";import"./Select.3a870b54.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./debounce.b911da70.js";import"./bootstrap.esm.c86673ca.js";const B={components:{Table:y,Search:N},props:["data","bid_requests","filters"],data(){return{appliedBidTableOptions:{titles:[{name:this.__("Bid Request Number"),className:"col-md-3"},{name:this.__("Applied at"),className:"col-md-3"},{name:this.__("Bidding Amount"),className:"col-md-2  text-end"},{name:this.__("Earning"),className:"col-md-2 text-end"}]},tableOptions:{titles:[{name:this.__("Details"),className:"col-md-3"},{name:this.__("Posted"),className:"col-md-3"},{name:this.__("Status"),className:"col-md-2"},{name:this.__("Budget"),className:"col-md-2 text-end"}]}}}},S={class:"container page-container"},q={class:"row"},w={key:0,class:"col-md-12"},x={class:"border rounded-3 p-3 mb-4"},A={class:"float-none w-auto px-3 fs-8 text-success"},O={class:"col-md-3"},C={class:"col-md-2"},D={class:"col-md-2 text-end"},V={class:"col-md-2 text-end"},L={class:"col-md-12"},M={class:"h5 bg-light p-2"},P=t("hr",null,null,-1),R={class:"col-md-3"},E={class:"col-md-9"},F={class:"data-container"},H={class:"col-md-3"},I={class:"col-md-2"},j={class:"col-md-3"},z={key:0,class:"badge text-bg-danger"},G={key:1,class:"badge text-bg-success"},J={class:"col-md-2 text-end"};function K(a,Q,o,U,m,W){const b=i("AppHead"),g=i("PageTitle"),u=i("Link"),h=i("Table"),k=i("Search");return n(),l(r,null,[d(b,{title:o.data.title},null,8,["title"]),t("div",S,[d(g,{title:o.data.title},null,8,["title"]),t("div",q,[o.data.applied_bids.length>0?(n(),l("div",w,[t("fieldset",x,[t("legend",A,s(a.__("Applied")),1),d(h,{options:m.appliedBidTableOptions,tableStyle:"table-striped"},{default:c(()=>[(n(!0),l(r,null,p(o.data.applied_bids,(e,_)=>(n(),l("tr",{class:"mb-2",key:_},[t("td",O,[d(u,{href:a.route("author.bidRequests.show",e.bid_request.uuid)},{default:c(()=>[f(s(e.bid_request.number),1)]),_:2},1032,["href"])]),t("td",C,s(a.localDate(e.created_at)),1),t("td",D,s(a.formatMoney(e.total)),1),t("td",V,s(a.formatMoney(e.author_payment_amount)),1)]))),128))]),_:1},8,["options"])])])):T("",!0),t("div",L,[t("div",M,s(a.__("Tasks available for bidding")),1),P]),t("div",R,[d(k,{data:o.data,filters:o.filters.filters,only:["bid_requests","filters"]},null,8,["data","filters"])]),t("div",E,[t("div",F,[d(h,{options:m.tableOptions,links:o.bid_requests.links,total:o.bid_requests.total},{default:c(()=>[(n(!0),l(r,null,p(o.bid_requests.data,(e,_)=>(n(),l("tr",{class:"mb-2",key:_},[t("td",H,[d(u,{href:a.route("author.bidRequests.show",e.uuid)},{default:c(()=>[f(s(e.number),1)]),_:2},1032,["href"]),t("div",null,s(e.task.service.name),1)]),t("td",I,s(a.localDate(e.created_at)),1),t("td",j,[e.is_closed?(n(),l("span",z,s(a.__("Closed")),1)):(n(),l("span",G,s(a.__("Open")),1))]),t("td",J,s(a.formatMoney(e.budget)),1)]))),128))]),_:1},8,["options","links","total"])])])])])],64)}const nt=v(B,[["render",K]]);export{nt as default};