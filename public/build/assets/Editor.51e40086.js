import{_ as m,g as u,y as p,f as r,o as f,b as _,a,w as c}from"./app.4060d334.js";import{S as b}from"./SelectUser.5a0f8fb7.js";import{S}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const k={props:["data","task"],components:{SelectUser:b,SubmitButton:S},data(){return{form:this.$inertia.form({editor_id:this.task.editor_id})}}};function B(s,t,o,V,e,U){const n=r("SelectUser"),d=r("SubmitButton"),l=r("Modal");return f(),u(l,{title:o.data.title,size:"small"},{default:p(()=>[_("form",{onSubmit:t[1]||(t[1]=c(i=>e.form.post(s.route("admin.tasks.update.editor",o.task.uuid)),["prevent"]))},[a(n,{options:o.data.assignees,modelValue:e.form.editor_id,"onUpdate:modelValue":t[0]||(t[0]=i=>e.form.editor_id=i),label:s.__("Editor for the task"),clearable:!0,name:"editor_id"},null,8,["options","modelValue","label"]),a(d,{disabled:e.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const C=m(k,[["render",B]]);export{C as default};