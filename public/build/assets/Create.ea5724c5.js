import{I as u}from"./Input.47b7b4a9.js";import{_ as p,g as d,y as f,f as n,o as _,b as c,a as i,w as b}from"./app.4060d334.js";import{S as x}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const g={components:{Input:u,SubmitButton:x},props:["data","existing_record"],data(){return{form:this.$inertia.form(this.prepareForm())}},methods:{prepareForm(){let t={name:null};return this.existing_record&&(t={...t,...this.existing_record}),t}}};function B(t,o,r,h,e,S){const s=n("Input"),a=n("SubmitButton"),l=n("Modal");return _(),d(l,{title:r.data.title},{default:f(()=>[c("form",{onSubmit:o[1]||(o[1]=b(m=>r.existing_record?e.form.patch(r.data.urls.submit_form):e.form.post(r.data.urls.submit_form),["prevent"]))},[i(s,{modelValue:e.form.name,"onUpdate:modelValue":o[0]||(o[0]=m=>e.form.name=m),name:"name",label:t.__("Name"),required:!0},null,8,["modelValue","label"]),i(a,{disabled:e.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const N=p(g,[["render",B]]);export{N as default};
