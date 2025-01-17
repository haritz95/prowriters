import v from"./BidLayout.d91df6c6.js";import{T as N}from"./Table.afdff20f.js";import q from"./Search.0c555ce2.js";import{_ as x,g as B,y as c,f as n,o as d,c as i,b as e,a as r,F as g,r as T,t as a,d as l,h as u}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";import"./Pagination.484cfb51.js";import"./Input.47b7b4a9.js";import"./CheckBox.949c07b7.js";import"./Select.3a870b54.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./debounce.b911da70.js";const C={props:["data","bids","filters"],components:{BidLayout:v,Table:N,Search:q},data(){return{tableOptions:{titles:[{name:this.__("Date"),className:"col-md-2"},{name:this.__("From"),className:"col-md-2"},{name:this.__("Delivery"),className:"col-md-2 text-end"},{name:this.__("Revisions"),className:"col-md-2 text-end"},{name:this.__("Bid Amount"),className:"col-md-2 text-end"},{name:this.__("Action"),className:"col-md-2 text-end"}]}}},methods:{accept(t){let _=this;this.confirmDialog(this.__("Accept the offer"),function(){_.$inertia.post(route("customer.bidRequests.accept",[_.data.bid_request.uuid,t]))})}}},L={key:0,class:"row"},D={class:"col-md-3"},S={class:"col-md-9"},w={class:"data-container"},A={class:"col-md-2"},V={class:"col-md-2"},F=["src"],R={class:"col-md-2 text-end"},O={class:"col-md-2 text-end"},z={class:"col-md-2 text-end"},E={class:"col-md-2 text-end"},M=["onClick"],Y={key:1},j={class:"text-center text-success"},G={class:"text-center"},H={key:0};function I(t,_,s,J,h,f){const b=n("Search"),m=n("Link"),p=n("Table"),k=n("BidLayout");return d(),B(k,{bid_request:s.data.bid_request,activeTab:"bids"},{default:c(()=>[s.data.is_hired?(d(),i("div",Y,[e("div",j,a(t.__("You have already hired for the task")),1),e("div",G,[s.data.task_url?(d(),i("p",H,[l(a(t.__("Click"))+" ",1),r(m,{href:s.data.task_url},{default:c(()=>[l(a(t.__("here")),1)]),_:1},8,["href"]),l(" "+a(t.__("to visit your task page")),1)])):u("",!0)])])):(d(),i("div",L,[e("div",D,[r(b,{data:s.data,filters:s.filters.filters,only:["bids","filters"]},null,8,["data","filters"])]),e("div",S,[e("div",w,[r(p,{options:h.tableOptions,links:s.bids.links,total:s.bids.total,text_no_record:t.__("The bids will appear here")},{default:c(()=>[(d(!0),i(g,null,T(s.bids.data,(o,y)=>(d(),i("tr",{class:"mb-2",key:y},[e("td",A,a(t.localDate(o.created_at)),1),e("td",V,[e("img",{class:"avatar rounded-circle me-2",src:o.author.small_avatar,loading:"lazy"},null,8,F),r(m,{href:t.route("customer.bidRequests.author",[s.data.bid_request.uuid,o.author.uuid])},{default:c(()=>[l(a(o.author.code),1)]),_:2},1032,["href"])]),e("td",R,a(o.duration_days)+" "+a(t.__("days")),1),e("td",O,a(o.number_of_revisions),1),e("td",z,a(t.formatMoney(o.total)),1),e("td",E,[s.data.bid_request.is_closed?u("",!0):(d(),i("button",{key:0,onClick:K=>f.accept(o.uuid),type:"button",class:"btn btn-sm btn-primary"},a(t.__("Accept")),9,M))])]))),128))]),_:1},8,["options","links","total","text_no_record"])])])]))]),_:1},8,["bid_request"])}const ot=x(C,[["render",I]]);export{ot as default};
