import d from"./AccountLayout.9152b040.js";import{_ as p,c as f,a,y as g,F as _,f as n,o as S,b as s,t as b,w as h}from"./app.4060d334.js";import{S as v}from"./Select.3a870b54.js";import{S as y}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const w={props:["data","user"],components:{AccountLayout:d,SubmitButton:y,Select:v},data(){return{form:this.$inertia.form({language:this.user.language}),formConfig:{preserveScroll:!1,onSuccess:()=>window.location.reload()}}}},B={class:"card"},A={class:"card-header h5"},L={class:"card-body"};function V(r,o,t,C,e,k){const u=n("AppHead"),i=n("Select"),c=n("SubmitButton"),m=n("AccountLayout");return S(),f(_,null,[a(u,{title:t.data.title},null,8,["title"]),a(m,{customer:t.user},{default:g(()=>[s("div",B,[s("div",A,b(t.data.title),1),s("div",L,[s("form",{onSubmit:o[1]||(o[1]=h(l=>e.form.patch(r.route("customer.account.language.update"),e.formConfig),["prevent"]))},[a(i,{options:t.data.dropdowns.languages,reduce_key:"iso_code",modelValue:e.form.language,"onUpdate:modelValue":o[0]||(o[0]=l=>e.form.language=l),label:r.__("Language"),required:!0,name:"language"},null,8,["options","modelValue","label"]),a(c,{disabled:e.form.disabled},null,8,["disabled"])],32)])])]),_:1},8,["customer"])],64)}const M=p(w,[["render",V]]);export{M as default};
