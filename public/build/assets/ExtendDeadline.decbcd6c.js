import{_ as m,g as p,y as f,f as n,o as u,b as c,a,w as _}from"./app.4060d334.js";import{S as b}from"./Select.3a870b54.js";import{S}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const B={props:["task","data"],components:{Select:b,SubmitButton:S},data(){return{form:this.$inertia.form({deadline:null}),formConfig:{preserveScroll:!1}}}};function V(t,o,l,v,e,w){const d=n("Select"),i=n("SubmitButton"),s=n("Modal");return u(),p(s,{title:t.__("Extend Deadline")},{default:f(()=>[c("form",{onSubmit:o[1]||(o[1]=_(r=>e.form.post(l.data.urls.submit_form,e.formConfig),["prevent"]))},[a(d,{options:l.data.dropdowns.deadline_options,modelValue:e.form.deadline,"onUpdate:modelValue":o[0]||(o[0]=r=>e.form.deadline=r),label:t.__("Deadline"),required:!0,name:"deadline",placeholder:t.__("Select to add")},null,8,["options","modelValue","label","placeholder"]),a(i,{disabled:e.form.disabled},null,8,["disabled"])],32)]),_:1},8,["title"])}const h=m(B,[["render",V]]);export{h as default};