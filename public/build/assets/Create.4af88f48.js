import{I as d}from"./Input.47b7b4a9.js";import{_ as p,g as s,y as _,f as m,o as u,b,a as i,h as V,w}from"./app.4060d334.js";import{S as v}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const c={components:{Input:d,SubmitButton:v},props:["data","existing_record"],data(){return{form:this.$inertia.form(this.prepareForm()),formConfig:{onSuccess:()=>this.form.reset()}}},methods:{prepareForm(){let n={name:null,number_of_revisions_allowed:null,price:0,url_query_parameters:this.data.url_query_parameters};return this.existing_record&&(n={...n,...this.existing_record}),n}}};function g(n,e,t,y,o,N){const l=m("Input"),a=m("SubmitButton"),f=m("Modal");return u(),s(f,{title:t.data.title},{default:_(()=>[b("form",{onSubmit:e[5]||(e[5]=w(r=>t.existing_record?o.form.patch(t.data.urls.submit_form):o.form.post(t.data.urls.submit_form,o.formConfig),["prevent"]))},[i(l,{modelValue:o.form.name,"onUpdate:modelValue":e[0]||(e[0]=r=>o.form.name=r),name:"name",label:n.__("Name"),required:!0},null,8,["modelValue","label"]),t.data.show_price_field?(u(),s(l,{key:0,modelValue:o.form.price,"onUpdate:modelValue":e[1]||(e[1]=r=>o.form.price=r),name:"price",label:n.__("Price"),required:!0,onKeypress:e[2]||(e[2]=r=>n.onlyNumber(r,o.form.price))},null,8,["modelValue","label"])):V("",!0),i(l,{modelValue:o.form.number_of_revisions_allowed,"onUpdate:modelValue":e[3]||(e[3]=r=>o.form.number_of_revisions_allowed=r),name:"number_of_revisions_allowed",label:n.__("Number of revisions allowed"),required:!0,onKeypress:e[4]||(e[4]=r=>n.onlyNumber(r,o.form.number_of_revisions_allowed))},null,8,["modelValue","label"]),i(a,{disabled:o.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const x=p(c,[["render",g]]);export{x as default};