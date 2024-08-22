import f from"./ManageContentLayout.2cbbcbed.js";import{I as g}from"./Input.47b7b4a9.js";import{T as c}from"./TextArea.c22bdae9.js";import{_ as b,c as V,a as n,y as C,F as x,f as a,o as S,b as r,w as F}from"./app.4060d334.js";import{S as v}from"./Select.3a870b54.js";import{S as T}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import{F as h}from"./FileChooser.90bd4def.js";import{C as A}from"./ColorPicker.465488aa.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const B={props:["data","existing_record","content_language"],components:{ManageContentLayout:f,Input:g,SubmitButton:T,TextArea:c,FileChooser:h,Select:v,ColorPicker:A},data(){return{form:this.$inertia.form(this.prepareForm()),formConfig:{preserveScroll:!0,onSuccess:()=>this.form.reset()}}},methods:{prepareForm(){let o={title:null,sub_title:null,content:null,image:null,image_alt_text:null,image_position:null,appearance:{bg_color:null,text_color:null}};return this.existing_record&&(o={...o,...this.existing_record}),o}}},I={class:"row"},U={class:"col-md-6"},w={class:"col-md-6"};function y(o,e,m,M,t,k){const s=a("AppHead"),i=a("Input"),u=a("TextArea"),p=a("FileChooser"),_=a("SubmitButton"),d=a("ManageContentLayout");return S(),V(x,null,[n(s,{title:m.data.title},null,8,["title"]),n(d,{content_language:m.content_language,title:m.data.title},{default:C(()=>[r("form",{onSubmit:e[5]||(e[5]=F(l=>t.form.patch(o.route("admin.manage.content.homepage.section.hero.update",m.content_language)),["prevent"]))},[n(i,{modelValue:t.form.title,"onUpdate:modelValue":e[0]||(e[0]=l=>t.form.title=l),label:o.__("Title"),name:"title",required:!0},null,8,["modelValue","label"]),n(i,{modelValue:t.form.sub_title,"onUpdate:modelValue":e[1]||(e[1]=l=>t.form.sub_title=l),label:o.__("Sub Title"),name:"sub_title"},null,8,["modelValue","label"]),n(u,{modelValue:t.form.content,"onUpdate:modelValue":e[2]||(e[2]=l=>t.form.content=l),label:o.__("Content"),name:"content"},null,8,["modelValue","label"]),r("div",I,[r("div",U,[n(p,{modelValue:t.form.image,"onUpdate:modelValue":e[3]||(e[3]=l=>t.form.image=l),label:o.__("Image"),name:"image"},null,8,["modelValue","label"])]),r("div",w,[n(i,{modelValue:t.form.image_alt_text,"onUpdate:modelValue":e[4]||(e[4]=l=>t.form.image_alt_text=l),label:o.__("Image Alt Text"),name:"image_alt_text"},null,8,["modelValue","label"])])]),n(_,{disabled:t.form.processing},null,8,["disabled"])],32)]),_:1},8,["content_language","title"])],64)}const K=b(B,[["render",y]]);export{K as default};
