import d from"./ManageContentLayout.2cbbcbed.js";import{I as _}from"./Input.47b7b4a9.js";import{T as f}from"./TextArea.c22bdae9.js";import{_ as g,c,a as l,y as b,F as V,f as a,o as S,b as B,w as T}from"./app.4060d334.js";import{S as x}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const C={props:["data","existing_record","content_language"],components:{ManageContentLayout:d,Input:_,SubmitButton:x,TextArea:f},data(){return{form:this.$inertia.form(this.prepareForm()),formConfig:{preserveScroll:!0,onSuccess:()=>this.form.reset()}}},methods:{prepareForm(){let e={title:null,sub_title:null,meta_tags:null};return this.existing_record&&(e={...e,...this.existing_record}),e}}};function y(e,t,r,A,o,M){const i=a("AppHead"),m=a("Input"),s=a("TextArea"),u=a("SubmitButton"),p=a("ManageContentLayout");return S(),c(V,null,[l(i,{title:r.data.title},null,8,["title"]),l(p,{content_language:r.content_language,title:r.data.title},{default:b(()=>[B("form",{onSubmit:t[3]||(t[3]=T(n=>o.form.patch(e.route("admin.manage.content.systemPages.blog.update",r.content_language)),["prevent"]))},[l(m,{modelValue:o.form.title,"onUpdate:modelValue":t[0]||(t[0]=n=>o.form.title=n),label:e.__("Title"),name:"title",required:!0},null,8,["modelValue","label"]),l(m,{modelValue:o.form.sub_title,"onUpdate:modelValue":t[1]||(t[1]=n=>o.form.sub_title=n),label:e.__("Sub Title"),name:"sub_title",required:!0},null,8,["modelValue","label"]),l(s,{modelValue:o.form.meta_tags,"onUpdate:modelValue":t[2]||(t[2]=n=>o.form.meta_tags=n),label:e.__("Meta Tags"),name:"meta_tags"},null,8,["modelValue","label"]),l(u,{disabled:o.form.processing},null,8,["disabled"])],32)]),_:1},8,["content_language","title"])],64)}const q=g(C,[["render",y]]);export{q as default};
