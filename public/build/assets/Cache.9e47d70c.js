import l from"./SettingsLayout.4c1fc6b3.js";import u from"./ActionToolBar.efa9c694.js";import{I as c}from"./Input.47b7b4a9.js";import{_ as f,g as p,y as _,f as o,o as b,b as d,a as n,w as S}from"./app.4060d334.js";import{S as B}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./Menu.e7c29a6b.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const g={components:{SettingsLayout:l,ActionToolBar:u,Input:c,SubmitButton:B},props:["data"],data(){return{form:this.$inertia.form(),formConfig:{preserveScroll:!1,onSuccess:()=>this.form.reset()},toolbar:{title:this.data.title,hide_save_button:!0}}}};function h(s,e,r,C,t,v){const i=o("ActionToolBar"),a=o("SubmitButton"),m=o("SettingsLayout");return b(),p(m,{title:r.data.title},{default:_(()=>[d("form",{onSubmit:e[0]||(e[0]=S(x=>t.form.post(r.data.urls.submit_form,t.formConfig),["prevent"]))},[n(i,{toolbar:t.toolbar},null,8,["toolbar"]),n(a,{disabled:t.form.processing,button_text:s.__("Clear Cache")},null,8,["disabled","button_text"])],32)]),_:1},8,["title"])}const M=f(g,[["render",h]]);export{M as default};
