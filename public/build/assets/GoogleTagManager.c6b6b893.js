import p from"./SettingsLayout.4c1fc6b3.js";import g from"./ActionToolBar.efa9c694.js";import{I as _}from"./Input.47b7b4a9.js";import{_ as d,g as u,y as f,f as r,o as c,b,a as n,w as B}from"./app.4060d334.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./Menu.e7c29a6b.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const T={components:{SettingsLayout:p,ActionToolBar:g,Input:_},props:["data","records"],data(){return{form:this.$inertia.form(this.records),toolbar:{title:this.data.title}}}};function v(e,t,l,I,o,V){const s=r("ActionToolBar"),i=r("Input"),m=r("SettingsLayout");return c(),u(m,{title:l.data.title},{default:f(()=>[b("form",{onSubmit:t[1]||(t[1]=B(a=>o.form.post(e.route("admin.settings.googleTagManager.update")),["prevent"]))},[n(s,{disable_save_button:o.form.processing,toolbar:o.toolbar},null,8,["disable_save_button","toolbar"]),n(i,{modelValue:o.form.google_tag_id,"onUpdate:modelValue":t[0]||(t[0]=a=>o.form.google_tag_id=a),name:"google_tag_id",label:e.__("Google Tag Manager ID"),placeholder:e.__("Example: UA-34294382-6")},null,8,["modelValue","label","placeholder"])],32)]),_:1},8,["title"])}const x=d(T,[["render",v]]);export{x as default};
