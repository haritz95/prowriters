import{I as d}from"./Input.47b7b4a9.js";import{_ as p,g as f,y as h,f as a,o as b,b as c,a as r,w as k}from"./app.4060d334.js";import{S as V}from"./SelectUser.5a0f8fb7.js";import{S}from"./SubmitButton.94bb04f8.js";import{D as y}from"./DatePicker.67be254a.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const B={props:["data","task"],components:{Input:d,SelectUser:V,SubmitButton:S,DatePicker:y},data(){return{form:this.$inertia.form({author_id:this.task.author_id,author_payment_amount:this.task.author_payment_amount?parseFloat(this.task.author_payment_amount).toFixed(2):0,dead_line_for_author:this.task.dead_line_for_author})}}};function U(n,t,m,g,o,D){const l=a("SelectUser"),s=a("DatePicker"),u=a("Input"),i=a("SubmitButton"),_=a("Modal");return b(),f(_,{title:m.data.title,size:"small"},{default:h(()=>[c("form",{onSubmit:t[3]||(t[3]=k(e=>o.form.post(n.route("admin.tasks.update.assignee",m.task.uuid)),["prevent"]))},[r(l,{options:m.data.assignees,modelValue:o.form.author_id,"onUpdate:modelValue":t[0]||(t[0]=e=>o.form.author_id=e),label:n.__("Author"),clearable:!0,name:"author_id"},null,8,["options","modelValue","label"]),r(s,{modelValue:o.form.dead_line_for_author,"onUpdate:modelValue":t[1]||(t[1]=e=>o.form.dead_line_for_author=e),label:n.__("Due date for the Author"),name:"dead_line_for_author"},null,8,["modelValue","label"]),r(u,{modelValue:o.form.author_payment_amount,"onUpdate:modelValue":t[2]||(t[2]=e=>o.form.author_payment_amount=e),label:n.__("Payment Amount"),name:"author_payment_amount"},null,8,["modelValue","label"]),r(i,{disabled:o.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const N=p(B,[["render",U]]);export{N as default};