import x from"./SettingsLayout.4c1fc6b3.js";import U from"./ActionToolBar.efa9c694.js";import{I as k}from"./Input.47b7b4a9.js";import{_ as h,g as d,y as b,f as r,o as i,b as s,a,d as y,t as m,e as c,v as T,a1 as B,c as V,r as N,F as A,h as g,w as I}from"./app.4060d334.js";import{S as C}from"./Select.3a870b54.js";import{S as D}from"./SelectUser.5a0f8fb7.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./Menu.e7c29a6b.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const E={components:{SettingsLayout:x,ActionToolBar:U,Input:k,Select:C,SelectUser:D},props:["data","records"],data(){return{form:this.$inertia.form(this.records),toolbar:{title:this.data.title}}}},L={class:"mb-3"},K={class:"form-label"},M=s("span",{class:"required"},"*",-1),F={class:"text-muted"},O={class:"mb-3"},P={class:"form-label"},Q=s("span",{class:"required"},"*",-1),R={class:"input-group"},j=["value"];function z(l,o,n,G,t,H){const v=r("ActionToolBar"),S=r("v-select"),p=r("ValidationError"),f=r("Input"),_=r("Select"),w=r("SelectUser"),q=r("SettingsLayout");return i(),d(q,{title:n.data.title},{default:b(()=>[s("form",{onSubmit:o[12]||(o[12]=I(e=>t.form.post(n.data.urls.submit_form),["prevent"]))},[a(v,{disable_save_button:t.form.processing,toolbar:t.toolbar},null,8,["disable_save_button","toolbar"]),s("div",L,[s("label",K,[y(m(l.__("Business Operation Type"))+" ",1),M]),a(S,{reduce:e=>e.id,modelValue:t.form.business_operation_type,"onUpdate:modelValue":o[0]||(o[0]=e=>t.form.business_operation_type=e),options:n.data.dropdowns.business_operation_types,label:"name",clearable:!1,searchable:!1},{option:b(({name:e,description:u})=>[s("div",null,m(e),1),s("small",F,m(u),1)]),_:1},8,["reduce","modelValue","options"]),a(p,{name:"business_operation_type"})]),a(f,{modelValue:t.form.payout_amount_threshold,"onUpdate:modelValue":o[1]||(o[1]=e=>t.form.payout_amount_threshold=e),name:"payout_amount_threshold",label:l.__("Author payout amount threshold"),required:!0,onKeypress:o[2]||(o[2]=e=>l.onlyNumber(e,t.form.payout_amount_threshold))},null,8,["modelValue","label"]),s("div",O,[s("label",P,[y(m(l.__("Time to add to the deadline for each revision request"))+" ",1),Q]),s("div",R,[c(s("input",{"onUpdate:modelValue":o[3]||(o[3]=e=>t.form.dead_line_extension_by_value=e),type:"text",class:"form-control form-control-sm w-75",onKeypress:o[4]||(o[4]=e=>l.onlyNumber(e,t.form.dead_line_extension_by_value))},null,544),[[T,t.form.dead_line_extension_by_value]]),c(s("select",{class:"form-select form-select-sm w-25","onUpdate:modelValue":o[5]||(o[5]=e=>t.form.dead_line_extension_by_type=e)},[(i(!0),V(A,null,N(n.data.dropdowns.urgency_types,(e,u)=>(i(),V("option",{value:e.id,key:u},m(e.name),9,j))),128))],512),[[B,t.form.dead_line_extension_by_type]])]),a(p,{name:"value"})]),a(_,{options:n.data.dropdowns.quality_control_availability,modelValue:t.form.disable_quality_control,"onUpdate:modelValue":o[6]||(o[6]=e=>t.form.disable_quality_control=e),label:l.__("Disable Quality Control"),required:!0,name:"disable_quality_control"},null,8,["options","modelValue","label"]),a(_,{options:n.data.dropdowns.sales_tax_availability,modelValue:t.form.enable_sales_tax,"onUpdate:modelValue":o[7]||(o[7]=e=>t.form.enable_sales_tax=e),label:l.__("Enable Sales Tax"),name:"enable_sales_tax"},null,8,["options","modelValue","label"]),t.form.enable_sales_tax?(i(),d(f,{key:0,modelValue:t.form.sales_tax_rate,"onUpdate:modelValue":o[8]||(o[8]=e=>t.form.sales_tax_rate=e),name:"sales_tax_rate",label:l.__("Sales Tax Rate"),required:!0,onKeypress:o[9]||(o[9]=e=>l.onlyNumber(e,t.form.sales_tax_rate)),note:l.__("In Percentage")},null,8,["modelValue","label","note"])):g("",!0),t.form.business_operation_type!="bidding"?(i(),d(_,{key:1,options:n.data.dropdowns.find_work_for_authors_availability,modelValue:t.form.enable_self_assigning_tasks,"onUpdate:modelValue":o[10]||(o[10]=e=>t.form.enable_self_assigning_tasks=e),label:l.__("Allow authors to assign themselves tasks when direct ordering is enabled"),required:!0,name:"enable_self_assigning_tasks"},null,8,["options","modelValue","label"])):g("",!0),a(w,{options:n.data.dropdowns.admin_users,modelValue:t.form.default_receipt_id_for_incoming_messages,"onUpdate:modelValue":o[11]||(o[11]=e=>t.form.default_receipt_id_for_incoming_messages=e),label:l.__("Default receipt for incoming messages from authors"),required:!0,name:"default_receipt_id_for_incoming_messages"},null,8,["options","modelValue","label"])],32)]),_:1},8,["title"])}const se=h(E,[["render",z]]);export{se as default};