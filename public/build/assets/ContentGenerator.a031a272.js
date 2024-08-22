import{I as w}from"./Input.47b7b4a9.js";import{T as v}from"./TextArea.c22bdae9.js";import{_ as y,f as i,o as l,c as u,b as t,t as p,a as _,F as c,r as U,g,h as f,d as k,w as S}from"./app.4060d334.js";import{S as F}from"./Select.3a870b54.js";import{S as T}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";const x={props:["data","existing_record"],components:{Input:w,TextArea:v,Select:F,SubmitButton:T},data(){return{fields:[],form:this.$inertia.form(this.generalFields())}},methods:{generalFields(){return{language_id:"English",tone_id:"Candid",use_case_id:null}},handleUseCaseChange(){let s=this.form.use_case_id,n=this.generalFields();if(s){n=this.form;let a=s?this.data.dropdowns.fields[s]:[];this.fields=a;for(let r=0;r<a.length;r++)n[a[r].name]=null}this.form=this.$inertia.form(n)}}},B={class:"vh-100",style:{background:"#f9f9f9"}},I={class:"p-2",style:{background:"#efefef"}},A={class:"row"},N={class:"col-md-6"},G={class:"col-md-6"},E={class:"d-grid gap-2"},L=["disabled"],D=t("i",{class:"fa-solid fa-arrow-right-long"},null,-1);function M(s,n,a,r,o,h){const d=i("Select"),b=i("Input"),V=i("TextArea");return l(),u("div",B,[t("div",I,p(s.__("Generate Content using AI")),1),t("form",{onSubmit:n[3]||(n[3]=S(e=>o.form.post(a.data.urls.generate_content),["prevent"]))},[t("div",A,[t("div",N,[_(d,{options:a.data.dropdowns.languages,modelValue:o.form.language_id,"onUpdate:modelValue":n[0]||(n[0]=e=>o.form.language_id=e),label:s.__("Language"),name:"language_id"},null,8,["options","modelValue","label"])]),t("div",G,[_(d,{searchable:!0,options:a.data.dropdowns.tones,modelValue:o.form.tone_id,"onUpdate:modelValue":n[1]||(n[1]=e=>o.form.tone_id=e),label:s.__("Tone"),name:"tone_id"},null,8,["options","modelValue","label"])])]),_(d,{searchable:!0,options:a.data.dropdowns.use_cases,modelValue:o.form.use_case_id,"onUpdate:modelValue":n[2]||(n[2]=e=>o.form.use_case_id=e),label:s.__("Choose use case"),name:"use_case_id",onChange:h.handleUseCaseChange},null,8,["options","modelValue","label","onChange"]),(l(!0),u(c,null,U(o.fields,(e,C)=>(l(),u(c,{key:C},[e.type=="input"?(l(),g(b,{key:0,modelValue:o.form[e.name],"onUpdate:modelValue":m=>o.form[e.name]=m,name:e.name,label:e.label,placeholder:e.placeholder},null,8,["modelValue","onUpdate:modelValue","name","label","placeholder"])):f("",!0),e.type=="textarea"?(l(),g(V,{key:1,modelValue:o.form[e.name],"onUpdate:modelValue":m=>o.form[e.name]=m,name:e.name,label:e.label,placeholder:e.placeholder,rows:e.rows?e.rows:2},null,8,["modelValue","onUpdate:modelValue","name","label","placeholder","rows"])):f("",!0)],64))),128)),t("div",E,[t("button",{class:"btn btn-primary",type:"submit",disabled:o.form.processing},[k(p(s.__("Generate Content"))+" ",1),D],8,L)])],32)])}const O=y(x,[["render",M]]);export{O as C};
