import{I as p}from"./Input.47b7b4a9.js";import{_ as u,g as d,y as f,f as m,o as _,b,a as l,w as c}from"./app.4060d334.js";import{S as g}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const V={components:{Input:p,SubmitButton:g},props:["data","existing_record"],data(){return{form:this.$inertia.form(this.prepareForm())}},methods:{prepareForm(){let e={name:null,percentage:null};return this.existing_record&&(e={...e,...this.existing_record}),e}}};function B(e,t,n,M,o,S){const a=m("Input"),i=m("SubmitButton"),s=m("Modal");return _(),d(s,{title:n.data.title},{default:f(()=>[b("form",{onSubmit:t[3]||(t[3]=c(r=>n.existing_record?o.form.patch(n.data.urls.submit_form):o.form.post(n.data.urls.submit_form),["prevent"]))},[l(a,{modelValue:o.form.name,"onUpdate:modelValue":t[0]||(t[0]=r=>o.form.name=r),name:"name",label:e.__("Name"),required:!0},null,8,["modelValue","label"]),l(a,{modelValue:o.form.percentage,"onUpdate:modelValue":t[1]||(t[1]=r=>o.form.percentage=r),label:e.__("Markup percentage"),tooltip:e.__("Markup percentage to be added to the price per word"),name:"percentage",onKeypress:t[2]||(t[2]=r=>e.onlyNumber(r,o.form.percentage)),required:!0},null,8,["modelValue","label","tooltip"]),l(i,{disabled:o.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const y=u(V,[["render",B]]);export{y as default};
